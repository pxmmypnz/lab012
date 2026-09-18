<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Psr\Http\Message\ServerRequestInterface;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\ShopController;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends SearchableController
{
    #[\Override()]
    function getQuery(): Builder
    {
        return Product::orderBy('code');
    }

    #[\Override()]
    function getFilterOptions(): array
    {
        $options = parent::getFilterOptions();

        return [
            'term' => [
                ...$options['term'],
                'category' => static fn(Builder $query, string $word) =>
                $query->whereHas(
                    'category',
                    static fn(Builder $categoryQuery) =>
                    $categoryQuery->where('name', 'LIKE', "%{$word}%"),
                ),
            ],
        ];
    }

    #[\Override()]
    function prepareCriteria(array $criteria): array
    {
        return [
            ...parent::prepareCriteria($criteria),
            'minPrice' => (($criteria['minPrice'] ?? null) === null)
                ? null
                : (float) $criteria['minPrice'],
            'maxPrice' => (($criteria['maxPrice'] ?? null) === null)
                ? null
                : (float) $criteria['maxPrice'],
        ];
    }

    private function toNullableFloat(mixed $value): ?float
    {
        return $value === null || $value === '' ? null : (float) $value;
    }

    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria)
            ->with(['category'])
            ->withCount('shops');
        return view('products.list', [
            'criteria' => $criteria,
            'products' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    function view(string $product): View
    {
        $productModel = $this->find($product)->load('category');

        return view('products.view', [
            'product' => $productModel,
        ]);
    }

    function showCreateForm(): View
    {
        return view('products.create-form', [
            'categories' => Category::orderBy('code')->get(),
        ]);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        $data = $request->getParsedBody();
        $category = Category::where('code', $data['category'])->firstOrFail();
        unset($data['category']);

        $product = new Product();
        $product->fill($data);
        $product->category()->associate($category);
        $product->save();

        return redirect()->route('products.view', [
            'product' => $product->code,
        ]);
    }

    function showUpdateForm(string $productCode): View
    {
        $product = $this->find($productCode);

        return view('products.update-form', [
            'product' => $product,
            'categories' => Category::orderBy('code')->get(),
        ]);
    }

    function update(
        string $productCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $product = $this->find($productCode);
        $data = $request->getParsedBody();
        $category = Category::where('code', $data['category'])->firstOrFail();
        unset($data['category']);

        $product->fill($data);
        $product->category()->associate($category);
        $product->save();

        return redirect()->route('products.view', [
            'product' => $product->code,
        ]);
    }

    function delete(string $productCode): RedirectResponse
    {
        $product = $this->find($productCode);
        $product->delete();

        return redirect()->route('products.index');
    }

    function filterByMinPrice(Builder|Relation $query, float $minPrice): void
    {
        $query->where('price', '>=', $minPrice);
    }

    function filterByMaxPrice(Builder|Relation $query, float $maxPrice): void
    {
        $query->where('price', '<=', $maxPrice);
    }

    #[\Override()]
    function filter(
        Builder|Relation $query,
        array $criteria,
        array $filterOptions,
    ): void {
        parent::filter($query, $criteria, $filterOptions);

        if ($criteria['minPrice'] !== null) {
            $this->filterByMinPrice($query, $criteria['minPrice']);
        }

        if ($criteria['maxPrice'] !== null) {
            $this->filterByMaxPrice($query, $criteria['maxPrice']);
        }
    }

    function viewShops(
        string $productCode,
        ServerRequestInterface $request,
    ): View {
        $product = $this->find($productCode);
        $shopController = resolve(ShopController::class);
        $criteria = $shopController->prepareCriteria($request->getQueryParams());
        $query = $product->shops()->withCount('products');
        $shopController->filter(
            $query,
            $criteria,
            $shopController->getFilterOptions(),
        );

        return view('products.view-shops', [
            'criteria' => $criteria,
            'product' => $product,
            'shops' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    private function filterOutShopByProduct(
        Builder|Relation $shopQuery,
        Product $product,
    ): void {
        $shopQuery->whereDoesntHave(
            'products',
            static function (Builder $productQuery) use ($product): void {
                $productQuery->where('code', $product->code);
            },
        );
    }

    function showAddShopsForm(
        string $productCode,
        ServerRequestInterface $request,
    ): View {
        $product = $this->find($productCode);
        $shopController = resolve(ShopController::class);
        $criteria = $shopController->prepareCriteria($request->getQueryParams());
        $query = $shopController->getQuery()->withCount('products');
        $this->filterOutShopByProduct($query, $product);
        $shopController->filter(
            $query,
            $criteria,
            $shopController->getFilterOptions(),
        );

        return view('products.add-shops-form', [
            'criteria' => $criteria,
            'product' => $product,
            'shops' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    function addShop(
        string $productCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $product = $this->find($productCode);
        $shopController = resolve(ShopController::class);
        $data = $request->getParsedBody();
        $shopQuery = $shopController->getQuery();
        $this->filterOutShopByProduct($shopQuery, $product);
        $shop = $shopQuery->where('code', $data['shop'])->firstOrFail();
        $product->shops()->attach($shop);

        return redirect()->back();
    }

    function removeShop(
        string $productCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $product = $this->find($productCode);
        $data = $request->getParsedBody();
        $shop = $product->shops()->where('code', $data['shop'])->firstOrFail();
        $product->shops()->detach($shop);

        return redirect()->back();
    }
}
