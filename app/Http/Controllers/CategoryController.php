<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
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
                'code' => static fn(Builder $query, string $word) =>
                $query->where('code', 'LIKE', "%{$word}%"),

                'name' => static fn(Builder $query, string $word) =>
                $query->where('name', 'LIKE', "%{$word}%"),
            ],
        ];
    }

    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', Category::class);
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria)->withCount('products');

        return view('categories.list', [
            'criteria' => $criteria,
            'categories' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    function showCreateForm(): View
    {
        Gate::authorize('create', Category::class);
        return view('categories.create-form');
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);
        try {
            $category = Category::create($request->getParsedBody());

            if (session()->has('bookmarks.categories.create')) {
                session()->put(
                    'bookmarks.categories.view',
                    session()->get('bookmarks.categories.create'),
                );
            }
            session()->forget('bookmarks.categories.create');

            return redirect()->route('categories.view', [
                'category' => $category->code,
            ])->with('status', "Category {$category->code} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function view(string $category): View
    {
        $categoryModel = $this->find($category);
        Gate::authorize('view', $categoryModel);

        return view('categories.view', [
            'category' => $categoryModel,
        ]);
    }

    function showUpdateForm(string $category): View
    {
        $categoryModel = $this->find($category);
        Gate::authorize('update', $categoryModel);

        return view('categories.update-form', [
            'category' => $categoryModel,
        ]);
    }

    function update(
        string $categoryCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $category = $this->find($categoryCode);
        Gate::authorize('update', $category);

        try {
            $category->fill($request->getParsedBody());
            $category->save();

            if (session()->has('bookmarks.categories.update')) {
                session()->put(
                    'bookmarks.categories.view',
                    session()->get('bookmarks.categories.update'),
                );
            }
            session()->forget('bookmarks.categories.update');

            return redirect()->route('categories.view', [
                'category' => $category->code,
            ])->with('status', "Category {$category->code} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    function delete(string $category): RedirectResponse
    {
        $categoryModel = $this->find($category);
        Gate::authorize('delete', $categoryModel);

        try {
            $categoryModel->delete();

            return redirect(
                session()->get('bookmarks.categories.delete') ?? route('categories.view', ['category' => $category]),
            )->with('status', "Category {$categoryModel->code} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    /**
     * View products that belong to the current category.
     */
    function viewProducts(
        string $categoryCode,
        ServerRequestInterface $request,
    ): View {
        $category = $this->find($categoryCode);
        Gate::authorize('view', $category);
        $productController = resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());

        $query = $category->products()
            ->with(['category'])
            ->withCount('shops');

        $filterOptions = $productController->getFilterOptions();
        $filterOptions['term'] = [
            'name' => static fn(Builder $query, string $word) =>
                $query->where('name', 'LIKE', "%{$word}%"),
        ];

        $productController->filter(
            $query,
            $criteria,
            $filterOptions,
        );

        return view('categories.view-products', [
            'criteria' => $criteria,
            'category' => $category,
            'products' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    /**
     * Display form/list to add products to the category.
     * Excludes products already belonging to this specific category.
     */
    function showAddProductsForm(
        string $categoryCode,
        ServerRequestInterface $request,
    ): View {
        $category = $this->find($categoryCode);
        Gate::authorize('update', $category);
        $productController = resolve(ProductController::class);
        $criteria = $productController->prepareCriteria($request->getQueryParams());

        // Exclude products already associated with this category ID
        $query = $productController->getQuery()
            ->where(function (Builder $builder) use ($category) {
                $builder->whereNull('category_id')
                    ->orWhere('category_id', '!=', $category->getKey());
            })
            ->with(['category'])
            ->withCount('shops');

        $productController->filter(
            $query,
            $criteria,
            $productController->getFilterOptions(),
        );

        return view('categories.add-products-form', [
            'criteria' => $criteria,
            'category' => $category,
            'products' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    /**
     * Associate product with category.
     */
    function addProduct(
        string $categoryCode,
        ServerRequestInterface $request,
    ): RedirectResponse {
        $category = $this->find($categoryCode);
        Gate::authorize('update', $category);
        try {
            $data = $request->getParsedBody();

            $product = Product::where('code', $data['product'])->firstOrFail();
            $product->category()->associate($category);
            $product->save();

            return redirect()->back()
                ->with('status', "Product {$product->code} was added to Category {$category->code}.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }
}
