@extends('layouts.layout-admin')
@section('page_heading','Infusionsoft Settings - Products')

@section('style')
<style>
    .modal-lg {
        max-width: 1100px !important;
    }
    .modal-lg-new {
        max-width: 1000px !important;
        top: 150px !important;
    }
</style>
@endsection

@section('section')
<admin-index-infusionsoft-settings-products-component inline-template>
    <div class="col-sm-12">
        <div class="row">

        </div>

        <div
            class="row panel panel-default vld-parent"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">

              <loading :active.sync="isLoadingInfusionsoftProductList" :can-cancel="false" :is-full-page="false"> </loading>
              <table class="table table-hover custom-table-css">
                  <thead>
                      <th>Products</th>
                      <th>Subscription Plans</th>
                  </thead>
                  <tbody v-if="allInfusionsoftProducts.length > 0">
                      <tr v-for="product,key in allInfusionsoftProducts">
                        <td>
                            <p><b>@{{product.product_name}}</b></p>
                            <p>@{{product.product_short_desc}}</p>
                        </td>
                        <td>
                            <table>
                              <tr>
                                  <td>Tax</td>
                                  <td>Price </td>
                                  <td>Total</td>
                                  <td>Status</td>
                                  <td>Actions</td>
                              </tr>
                              <tbody v-if="product.subscription_plans.length > 0">
                                  <tr v-for="subscription_plan,spkey in product.subscription_plans">
                                    <td>
                                      <b>@{{subscription_plan.sales_tax}}</b>
                                      <i
                                        class="fas fa-edit text-info"
                                        style="cursor: pointer;"
                                        alt="Edit"
                                        title="Edit"

                                        :data-id="subscription_plan.db_id"
                                        data-toggle="modal"
                                        data-backdrop='static'
                                        data-keyboard="false"
                                        data-target="#editTaxModal"
                                        @click="editSalesTax(subscription_plan.db_id,subscription_plan.sales_tax)"
                                      >
                                    </td>
                                    <td><b>@{{subscription_plan.product_price}}</b></td>
                                    <td><b>@{{subscription_plan.plan_price}}</b></td>
                                    <td>
                                      <span v-if="subscription_plan.active != 1">
                                          <span class="bg-danger" style="padding:5px 25px"> Inactive </span>
                                      </span>
                                      <span v-else>
                                          <span class="bg-success" style="padding:5px 25px"> Active </span>
                                      </span>
                                    </td>
                                    <td>
                                      <span v-if="subscription_plan.active == 1">
                                          <span v-if="subscription_plan.is_hide == 0">
                                              <span @click="showHideProduct(subscription_plan.db_id)" class="btn btn-small btn-danger">
                                                <i class="fas fa-eye-slash"> </i>
                                                Hide
                                              </span>
                                          </span>
                                          <span v-else>
                                              <span @click="showHideProduct(subscription_plan.db_id)" class="btn btn-small btn-success">
                                                <i class="fas fa-eye"> </i>
                                                Show
                                              </span>
                                          </span>
                                      </span>
                                    </td>
                                  </tr>
                              </tbody>
                           </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>

            </div>
        </div>

        <div class="modal fade" id="editTaxModal" tabindex="-1" role="dialog" aria-labelledby="editTaxModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            <i class="fa fa-edit"></i> Edit Sales Tax
                        </h4>
                    </div>
                <form
                  class="form-horizontal was-validated"
                  @submit.prevent="saveSalesTax"
                >
                  @csrf
                    <div class="modal-body">
                        <div class="">
                            <input type="hidden" v-model="id" name="id">
                            <input
                                type="text"
                                name="salestax"
                                class="form-control"
                                v-model="salestax"
                            >
                            <span class="control-label text-danger" v-if="salestax_error">Sales Tax is required</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"> </i> Save
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa fa-close"> </i> Cancel
                        </button>
                    </div>
                  </form>
                </div>
            </div>
        </div>

    </div>

</admin-index-infusionsoft-settings-products-component>
@stop
