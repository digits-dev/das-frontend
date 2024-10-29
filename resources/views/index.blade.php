@include('partials.header')

<style>
    .bottomright {
        position: fixed;
        bottom: 0px;
        left: 0px;
    }

    .buttonModal {
        background-color: #3C8DBC;
        color: white;
        padding: 3px;
        margin: 5px;
        font-size: 0.85em;
        width: 90%;
    }
    .modal-dialog {
        max-width: unset !important;
        width: 90% !important;
    }
</style>

<div class="row" style="width:100%;height:100%;text-align: -webkit-center;margin: 0 0px;">
    <div class="search" style="width: 100%;place-content: center;">

        <div class="add-pr" style="margin: 0 30px; margin-top: 60px; margin-bottom: -116px;">
            <a class="btn hvr-hover button-create" style="background-color: #3C8DBC;" href="{{ route('createWarranty') }}">Create a Warranty Request</a>
        </div>

        <h1 class="title-homepage" style="font-size: 2.3em;margin-top: 141px;"><strong>Track Your Warranty Request</strong></h1>

        <form class="searchform" method="post" action="{{ route('checkWarranty') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="text" placeholder="Enter Tracking Number..." name="search">
            <button class="hvr-hover" type="submit" style="background-color: #3C8DBC;"><i class="fa fa-search"></i></button>
        </form>

        @if(!empty($result['route']))
            @if($result['trackingresult'])
                <!-- Start of Search Result  -->
                <div class="wishlist-box-main" style="margin: 0 30px; width: 50%;place-content: center;">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-main table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #3C8DBC;">Reference Number</th>
                                            <th style="background-color: #3C8DBC;">Status</th>
                                            <th style="background-color: #3C8DBC;">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color:white;">
                                        @if(count($result['trackingresult']) > 0)
                                            @foreach($result['trackingresult'] as $search)
                                            <tr>
                                                <td class="name-pr">
                                                    <a href="#">
                                                        {{$search->return_reference_no}}
                                                    </a>
                                                </td>
                                                <td class="quantity-box">
                                                    <p>{{ $search->warranty_status }}</p>
                                                </td>
                                                <td class="quantity-box">
                                                    <p>{{$search->date}}</p>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="quantity-box" colspan="3">
                                                    <p align="center">No result found!</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Search Result  -->
            @endif
        @endif
    </div>

    <div class="bottomright">
        <button type="button" class="btn buttonModal hvr-hover" data-toggle="modal" data-target="#privacyModalLong">
            PRIVACY
        </button>
        <br>
        <button type="button" class="btn buttonModal hvr-hover" data-toggle="modal" data-target="#TOUModalLong">
            TERMS OF USE
        </button>
    </div>

    <!-- Privacy Modal -->
    <div class="modal fade" id="privacyModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle"><strong>PRIVACY STATEMENT</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align:left; font-size: 0.95em; font-family:Arial; color:#212121; margin: 0 20px;">
                    @include('warranty.privacy_statement')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms of Use Modal -->
    <div class="modal fade" id="TOUModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle"><strong>TERMS OF USE</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align:left; font-size: 0.95em; font-family:Arial; color:#212121; margin: 0 20px;">
                    @include('warranty.terms_of_use')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')
