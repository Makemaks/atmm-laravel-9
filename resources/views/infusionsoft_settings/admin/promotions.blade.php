@extends('layouts.layout-admin')
@section('page_heading','Infusionsoft Settings - Promotions')

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
<admin-index-infusionsoft-settings-promotions-component inline-template>
    <div class="col-sm-12">
        <div class="row">

        </div>

        <div
            class="row panel panel-default vld-parent"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">

              <loading :active.sync="isLoadingInfusionsoftPromotionList" :can-cancel="false" :is-full-page="false"> </loading>
              <table class="table table-hover custom-table-css">
                  <thead>
                      <th>Promo Code</th>
                  </thead>
                  <tbody v-if="allInfusionsoftPromotions.length > 0">
                      <tr v-for="promocode,key in allInfusionsoftPromotions">
                        <td><b>@{{promocode}}</b></td>
                      </tr>
                  </tbody>
              </table>
            </div>
        </div>



    </div>

</admin-index-infusionsoft-settings-promotions-component>
@stop
