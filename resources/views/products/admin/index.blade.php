@extends('layouts.layout-admin')
@section('page_heading','Products')
@section('section')
<admin-index-products-component inline-template>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-md-6">
                <searched :search="search" @gosearch="getProductList('search')"></searched>
            </div>
            <div class="col-md-6 text-right">
                <a type="button" class="btn btn-danger">
                    <i class="fa fa-trash"></i>&nbsp; Delete
                </a>
                <a type="button" href="/products/create" class="btn btn-primary" data-dismiss="modal">
                    <i class="fa fa-plus"></i>&nbsp; Create
                </a>
            </div>
        </div>
        <div
            class="row panel panel-default"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">
              <loading :active.sync="isLoadingProductList" :can-cancel="false" :is-full-page="false"> </loading>
              <table class="table table-hover custom-table-css">
                  <thead>
                      <th><input name="" type="checkbox"></th>
                      <th>
                        <a href="#" @click="sortProductList('nmi_api_plan_id')">
                            NMI PlanID
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>User Count</th>
                      <th>
                        <a href="#" @click="sortProductList('product_name')">
                            Name
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>
                        <a href="#" @click="sortProductList('product_price')">
                            Price
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>
                        <a href="#" @click="sortProductList('sales_tax')">
                            Sales Tax
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>ACTION</th>
                  </thead>
                  <tbody v-if="allProducts.length > 0">
                      <tr v-for="prod,key in allProducts">
                          <td><input type="checkbox" v-model="prod.checked" :checked="prod.checked"></td>
                          <td>@{{prod.nmi_api_plan_id}}</td>
                          <td>
                            <span v-if="prod.nmi_payments.length > 0">
                                <a href="/nmipayments">@{{prod.nmi_payments.length}}</a>
                            </span>
                          </td>
                          <td>@{{prod.product_name}}</td>
                          <td>@{{prod.product_price}}</td>
                          <td>@{{prod.sales_tax}}</td>
                          <td>
                            <a :href="'/products/'+prod.id+'/edit'" type="button" class="btn btn-success btn-circle">
                                <i class="fa fa-pencil-alt"></i>
                            </a>


                            <button
                                type="button"
                                id="delete_details"
                                :data-id="prod.id"
                                class="btn btn-danger btn-circle delete_details"
                                data-toggle="modal_test"
                                data-backdrop='static'
                                data-keyboard="false"
                                data-target="#exampleModalCenter"
                                @click="deleteProduct(prod.id,prod.nmi_payments.length)"
                            >
                                <i class="fa fa-trash"></i>
                            </button>

                          </td>
                      </tr>
                  </tbody>
              </table>
            </div>
        </div>


        <div class="row">
            <div style="float:right">
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getProductList()"></pagination>
            </div>
        </div>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            <i class="fa fa-trash"></i> Delete
                        </h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form ref="delete_form" action="" method="post">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger"> Delete </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</admin-index-products-component>
@stop
