@extends('layouts.master')

@section('page_header')
    Issue Medicine
@endsection

@section('content')
    <script src="{{asset('plugins/angularjs/angular.min.js')}}"></script>
    <script src="{{asset('js/services.js')}}"></script>
    <script src="{{asset('js/filters.js')}}"></script>
    <script src="{{asset('js/GlobalIssueMedicineController.js')}}"></script>

    <div class="container-fluid" ng-app="HIS">
        <div class="box box-primary" ng-controller="IssueMedicineController">

            <div class="box-body">
                {{-- Initialize the angular variables in a hidden field --}}
                <input type="hidden"
                       ng-init="baseUrl='{{url("/")}}';token='{{csrf_token()}}';loadAllPrescriptions()">

                {{--Success Mesage--}}
                <div class="alert alert-success alert-dismissable" ng-show="hasSuccess" ng-cloak>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    [[successMessage]]
                </div>


                {{-- Info message if there are no prescriptions to be issued --}}
                <div class="alert alert-info alert-dismissable" ng-if="prescriptions.length==0" ng-cloak>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> Sorry!</h4>
                    No Prescription to be issued at the moment.
                </div>

                {{-- Alert message to notify new prescriptions are available to be loaded --}}
                <div class="callout callout-warning" ng-show="hasAlert" ng-cloak>
                    <h4>New Prescriptions Available!</h4>
                    <p>There are new prescriptions available. Please load them.</p>
                    <button class="btn btn-default" ng-click="hasAlert=false;loadAllPrescriptions()">
                        Load
                    </button>
                </div>

                {{--Prescription--}}
                <div class="box box-primary box-solid" ng-repeat="prescription in prescriptions track by $index"
                     ng-cloak>
                    <div class="box-header">
                        <h4 class="box-title">
                            [[prescription.patient.first_name]] [[prescription.patient.last_name]]<br>
                            [[prescription.created_at | dateToISO | date:"EEEE, d/M/yy h:mm a"]]
                        </h4>
                    </div>

                    <div class="box-body">
                        <div class="alert alert-danger alert-dismissable" ng-show="prescription.hasError" ng-cloak>
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Oops!</h4>
                            [[error]]
                        </div>

                        <table class="table table-hover table-condensed table-bordered text-center">
                            <thead>
                            <tr class="success">
                                <th class="col-sm-4">Drug</th>
                                <th class="col-sm-5">Dose</th>
                                <th class="col-sm-3">Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="success" ng-repeat="prescribedDrug in prescription.prescription_drugs">
                                <td>[[prescribedDrug.drug.name]] ([[prescribedDrug.drug.quantity_type.drug_type]])</td>
                                <td>
                                    [[prescribedDrug.dosage.description]]<br>
                                    [[prescribedDrug.frequency.description]]<br>
                                    [[prescribedDrug.period.description]]
                                </td>
                                <td>
                                    <input class="form-control" type="number" step="0.01"
                                           ng-model="prescribedDrug.issuedQuantity" min="0">
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        {{--Input to add payment information--}}
                        <div class="container-fluid col-sm-12 margin">
                            <label class="col-sm-3 control-label text-right">Payment</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" min="0" ng-model="prescription.payment"
                                       step="0.01">
                            </div>
                        </div>
                        <div class="container-fluid col-sm-12 margin">
                            <label class="col-sm-3 control-label text-right">Remarks</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" ng-model="prescription.paymentRemarks"></textarea>
                            </div>
                        </div>


                    </div>

                    <div class="box-footer">
                        <button class="btn btn-lg btn-success pull-right" ng-click="issuePrescription([[$index]])">
                            Mark as Issued
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection