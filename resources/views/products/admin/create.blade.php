@extends('layouts.layout-admin')
@section('page_heading','Create')
@section('section')
<admin-create-product-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form
                    class="form-horizontal was-validated"
                    @submit.prevent="saveProduct"
                >
                    @csrf

                    <div
                        class="form-group"
                        :class="nmiapiplanid_error ? 'has-error has-feedback' : ''"
                    >
                        <label class="col-sm-2 control-label">NMI API Plan ID</label>
                        <div class="col-sm-8">
                            <input
                                type="text"
                                name="nmi_api_plan_id"
                                class="form-control"
                                placeholder="Enter Recurring Plan ID"
                                v-model="nmi_api_plan_id"
                            >
                            <span class="control-label" v-if="nmiapiplanid_error">NMI API Plan ID is required</span>
                        </div>
                    </div>

                    <div
                        class="form-group"
                        :class="productname_error ? 'has-error has-feedback' : ''"
                    >
                        <label class="col-sm-2 control-label">Product Name</label>
                        <div class="col-sm-8">
                            <input
                                type="text"
                                name="product_name"
                                class="form-control"
                                placeholder="Enter product name"
                                v-model="product_name"
                            >
                            <span class="control-label" v-if="productname_error">Product Name is required</span>
                        </div>
                    </div>


                    <div
                        class="form-group"
                        :class="productprice_error ? 'has-error has-feedback' : ''"
                    >
                        <label class="col-sm-2 control-label">Product Price</label>
                        <div class="col-sm-8">
                            <input
                                type="text"
                                name="product_price"
                                class="form-control"
                                placeholder="Enter product price"
                                v-model="product_price"
                            >
                            <span class="control-label" v-if="productprice_error">Product Price is required</span>
                        </div>
                    </div>


                    <div
                        class="form-group"
                        :class="salestax_error ? 'has-error has-feedback' : ''"
                    >
                        <label class="col-sm-2 control-label">Sales Tax</label>
                        <div class="col-sm-8">
                            <input
                                type="text"
                                name="sales_tax"
                                class="form-control"
                                placeholder="Enter sale tax"
                                v-model="sales_tax"
                            >
                            <span class="control-label" v-if="salestax_error">Tax is required</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</admin-create-product-component>
@stop
