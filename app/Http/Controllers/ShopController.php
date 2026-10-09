<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class ShopController extends SearchableController
{
    #[\Override()]
    function getQuery(): Builder
    {
        return Shop::orderBy('code');
    }

    #[\Override()]
    function getFilterOptions(): array
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

    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria)->withCount('products');

        return view('shops.list', [
            'criteria' => $criteria,
            'shops' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    function view(string $shop): View
    {
        $shopModel = Shop::where('code', $shop)->firstOrFail();

        return view('shops.view', [
            'shop' => $shopModel,
        ]);
    }

    function showCreateForm(): View
    {
        return view('shops.create-form');
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        $shop = Shop::create($request->getParsedBody());

        if (session()->has('bookmarks.shops.create')) {
            session()->put(
                'bookmarks.shops.view',
                session()->get('bookmarks.shops.create'),
            );
        }
        session()->forget('bookmarks.shops.create');

        return redirect()->route('shops.view', [
            'shop' => $shop->code,
        ])->with('status', "Shop {$shop->code} was created.");
    }

    function showUpdateForm(string $shop): View
    {
        $shopModel = $this->find($shop);

        return view('shops.update-form', [
            'shop' => $shopModel,
        ]);
    }

    function update(
        string $shop,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shopModel = $this->find($shop);

        $shopModel->fill($request->getParsedBody());
        $shopModel->save();

        if (session()->has('bookmarks.shops.update')) {
            session()->put(
                'bookmarks.shops.view',
                session()->get('bookmarks.shops.update'),
            );
        }
        session()->forget('bookmarks.shops.update');

        return redirect()->route('shops.view', [
            'shop' => $shopModel->code,
        ])->with('status', "Shop {$shopModel->code} was updated.");
    }

    function delete(string $shop): RedirectResponse
    {
        $shopModel = $this->find($shop);
        $shopModel->delete();

        return redirect(
            session()->get('bookmarks.shops.delete') ?? route('shops.view', ['shop' => $shop]),
        )->with('status', "Shop {$shopModel->code} was deleted.");
    }

    /**
     * Display products associated with the shop.
     * Prevents N+1 query problem by eager loading 'category' and counting 'shops'.
     */
    function viewProducts(
        string $shopCode,
        ServerRequestInterface $request,
    ): View {
        $shop = $this->find($shopCode);
        $productController = resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());

        // Eager load category to prevent N+1 queries when rendering product->category->name
        $query = $shop->products()
            ->with(['category'])
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

    /**
     * Filter out products already associated with this shop.
     */
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

    /**
     * Display form/list to add products not yet associated with the shop.
     * Searchable by term (including category name), minPrice, maxPrice.
     */
    function showAddProductsForm(
        string $shopCode,
        ServerRequestInterface $request,
    ): View {
        $shop = $this->find($shopCode);
        $productController = resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());

        // Eager load category to prevent N+1 queries
        $query = $productController->getQuery()
            ->with(['category'])
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

    /**
     * Attach product to shop using Many-to-Many relation ($shop->products()->attach($product)).
     */
    function addProduct(
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

        return redirect()->back()
            ->with('status', "Product {$product->code} was added to Shop {$shop->code}.");
    }

    /**
     * Detach product from shop using Many-to-Many relation ($shop->products()->detach($product)).
     */
    function removeProduct(
        string $shopCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        $data = $request->getParsedBody();

        $product = $shop->products()->where('code', $data['product'])->firstOrFail();
        $shop->products()->detach($product);

        return redirect()->back()
            ->with('status', "Product {$product->code} was removed from Shop {$shop->code}.");
    }
}
