<?php

namespace App\Http\Controllers\Web;

use App\Models\Products;
use App\Models\Nmipayments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Store;
use App\Http\Requests\Products\Update;
use Session;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      try {
          if(!$this->isCurrentUserAdmin())
              abort(404);

          if($request->ajax()){
            $products = $this->paginate($request, [
                'items' => (new Products())->with('nmiPayments'),
                's_fields' => ['product_name'],
                'sortBy' => $request->sort_field,
                'sortOrder' => $request->sort_order,
            ]);
            $response = [
                 'pagination' => [
                     'total' => $products->total(),
                     'per_page' => $products->perPage(),
                     'current_page' => $products->currentPage(),
                     'last_page' => $products->lastPage(),
                     'from' => $products->firstItem(),
                     'to' => $products->lastItem()
                 ],
                 'data' => $products
             ];
             return response()->json($response);
          } else {
              return view('products.admin.index');
          }

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      try {
          if(!$this->isCurrentUserAdmin())
              abort(404);

          return view('products.admin.create');
      } catch (\Throwable $th) {
          throw $th;
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
          try {
              if(!$this->isCurrentUserAdmin()) {
                  return response()->json('error', 404);
              } else
              {
                  if($request->nmi_api_plan_id == 1)
                  {
                    return response()->json('error', 404);
                  }
                  else{
                    $products = Products::create($request->all());
                    return response()->json('success', 200);
                  }
              }
          } catch (\Throwable $th) {
              throw $th;
          }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Products::findOrFail($id);
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            //$product = Products::findOrFail($id);
            //return view('products.admin.edit', ['products' => $product, ]);
            return view('products.admin.edit', ['id' => $id, ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
      try {
          if(!$this->isCurrentUserAdmin())
              abort(404);

          $products = Products::findOrFail($id);
          $products->update($request->all());

          return $products;

          //return back()->with(['success' => 'Products successfully updated.']);
      } catch (\Exception $th) {
          throw $th;
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            $product_user_id = explode(',' , $id);

            foreach($product_user_id as $id)
            {
              $product = Products::findOrFail($id);
              if($product != null || $product != '')
              {
                  if($product->nmi_api_plan_id == 1)
                  {
                    Session::flash('messageProductError', "There are payments made on this ($product->product_name) plan. Please remove first all the payments under this plan");
                    return back();
                  }
                  else
                  {
                    Session::flash('messageProductSuccess', "Product deleted successfully");
                    $product->delete();
                  }

              }
            }

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
