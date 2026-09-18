<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Http\RedirectResponse;

class ShopController extends SearchableController
{
    #[\Override()]
    function getQuery(): Builder
    {
        return Shop::orderBy('code');
    }

    public function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria)->withCount('products');

        return view('shops.list', [
            'criteria' => $criteria,
            'shops' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    public function view(string $shop): View
    {
        $shop = Shop::where('code', $shop)->firstOrFail();

        return view('shops.view', [
            'shop' => $shop,
        ]);
    }

    public function viewProducts(
        string $shopCode,
        ServerRequestInterface $request,
    ): View {
        $shop = $this->find($shopCode);
        $productController = resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $query = $shop->products()
            ->with('category')
            ->withCount('shops');
        $productController->filter(
            $query,
            $criteria,
            $productController->getFilterOptions(),
        );

        return view('shops.view-products', [
            'criteria' => $criteria,
            'shop' => $shop,
            'products' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    private function filterOutProductByShop(
        Builder|Relation $productQuery,
        Shop $shop,
    ): void {
        $productQuery->whereDoesntHave(
            'shops',
            static function (Builder $shopQuery) use ($shop): void {
                $shopQuery->whereKey($shop->getKey());
            },
        );
    }

    public function showAddProductsForm(
        string $shopCode,
        ServerRequestInterface $request,
    ): View {
        $shop = $this->find($shopCode);
        $productController = resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $query = $productController->getQuery()
            ->with('category')
            ->withCount('shops');
        $this->filterOutProductByShop($query, $shop);
        $productController->filter(
            $query,
            $criteria,
            $productController->getFilterOptions(),
        );
        return view('shops.add-products-form', [
            'criteria' => $criteria,
            'shop' => $shop,
            'products' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    public function addProduct(
        string $shopCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        $productController = resolve(ProductController::class);
        $data = $request->getParsedBody();
        $productQuery = $productController->getQuery();
        $this->filterOutProductByShop($productQuery, $shop);
        $product = $productQuery->where('code', $data['product'])->firstOrFail();
        $shop->products()->attach($product);

        return redirect()->back();
    }

    public function removeProduct(
        string $shopCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        $data = $request->getParsedBody();
        $product = $shop->products()->where('code', $data['product'])->firstOrFail();
        $shop->products()->detach($product);

        return redirect()->back();
    }

    #[\Override()]
    public function getFilterOptions(): array
    {
        $options = parent::getFilterOptions();

        return [
            'term' => [
                ...$options['term'],

                'owner' => static fn(Builder $query, string $word) =>
                $query->where('owner', 'LIKE', "%{$word}%"),

                'address' => static fn(Builder $query, string $word) =>
                $query->where('address', 'LIKE', "%{$word}%"),
            ],
        ];
    }

    public function showCreateForm(): View
    {
        return view('shops.create-form');
    }

    public function create(
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shop = Shop::create($request->getParsedBody());

        return redirect()->route('shops.view', [
            'shop' => $shop->code,
        ]);
    }

    public function showUpdateForm(string $shop): View
    {
        $shopModel = $this->find($shop);

        return view('shops.update-form', [
            'shop' => $shopModel,
        ]);
    }

    public function update(
        string $shop,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shopModel = $this->find($shop);

        $shopModel->fill($request->getParsedBody());
        $shopModel->save();

        return redirect()->route('shops.view', [
            'shop' => $shopModel->code,
        ]);
    }

    public function delete(string $shop): RedirectResponse
    {
        $shopModel = $this->find($shop);
        $shopModel->delete();

        return redirect()->route('shops.list');
    }
}
