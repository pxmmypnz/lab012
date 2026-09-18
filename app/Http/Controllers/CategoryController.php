<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController extends SearchableController
{
    #[\Override()]
    function getQuery(): Builder
    {
        return Category::orderBy('code');
    }

    #[\Override()]
    function getFilterOptions(): array
    {
        return [
            'term' => [
                'code' =>
                static fn(Builder $query, string $word)
                => $query->where('code', 'LIKE', "%{$word}%"),
                'name' =>
                static fn(Builder $query, string $word)
                => $query->where('name', 'LIKE', "%{$word}%"),
            ],
        ];
    }

    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria)->withCount('products');

        return view('categories.list', [
            'criteria' => $criteria,
            'categories' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    public function showCreateForm(): View
    {
        return view('categories.create-form');
    }

    public function create(ServerRequestInterface $request): RedirectResponse
    {
        $category = Category::create($request->getParsedBody());

        return redirect()->route('categories.view', [
            'category' => $category->code,
        ]);
    }

    public function view(string $category): View
    {
        $categoryModel = $this->find($category);

        return view('categories.view', [
            'category' => $categoryModel,
        ]);
    }

    public function showUpdateForm(string $category): View
    {
        return view('categories.update-form', [
            'category' => $this->find($category),
        ]);
    }

    public function update(
        string $categoryCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $category = $this->find($categoryCode);
        $category->fill($request->getParsedBody());
        $category->save();

        return redirect()->route('categories.view', [
            'category' => $category->code,
        ]);
    }

    public function delete(string $category): RedirectResponse
    {
        $this->find($category)->delete();

        return redirect()->route('categories.list');
    }

    public function viewProducts(
        string $categoryCode,
        ServerRequestInterface $request,
    ): View {
        $category = $this->find($categoryCode);
        $productController = resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $query = $category->products()->with('category')->withCount('shops');
        $filterOptions = $productController->getFilterOptions();
        unset($filterOptions['term']['category']);
        $productController->filter(
            $query,
            $criteria,
            $filterOptions,
        );

        return view('categories.view-products', [
            'category' => $category,
            'criteria' => $criteria,
            'products' => $query->orderBy('code')->paginate(static::MAX_ITEMS),
        ]);
    }

    private function filterOutProductByCategory(
        Builder|Relation $productQuery,
        Category $category,
    ): void {
        $productQuery->whereDoesntHave(
            'category',
            static function (Builder $categoryQuery) use ($category): void {
                $categoryQuery->whereKey($category->getKey());
            },
        );
    }

    public function showAddProductsForm(
        string $categoryCode,
        ServerRequestInterface $request,
    ): View {
        $category = $this->find($categoryCode);
        $productController = resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $query = $productController->getQuery()
            ->with('category')
            ->withCount('shops');
        $this->filterOutProductByCategory($query, $category);
        $productController->filter(
            $query,
            $criteria,
            $productController->getFilterOptions(),
        );

        return view('categories.add-products-form', [
            'category' => $category,
            'criteria' => $criteria,
            'products' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    public function addProduct(
        string $categoryCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $category = $this->find($categoryCode);
        $productController = resolve(ProductController::class);
        $data = $request->getParsedBody();
        $product = $productController->getQuery()
            ->where('code', $data['product'])
            ->firstOrFail();
        $product->category()->associate($category);
        $product->save();

        return redirect()->back();
    }
}
