@extends('layout.userlayout')
@section('content')
    <nav class="p-2 border-bottom d-flex align-items-center">
        <div class="filter-toggle d-flex align-items-center" onclick="openFilter()">
            <span class="mr-2" id="filter-toggler">
                <i class="fa fa-filter fa-lg"></i>
            </span>
            <h5 class="">Filter</h5>
        </div>
        <div class="mx-auto">
            Reimbursement Fee:
            <span class="" style="font-size: 20px;font-weight: 600;">${{ $reimfee }}</span>
        </div>
        <div class="ml-auto">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <h6 class="">
                <i class="fa fa-user"></i>
                {{ ucwords(session('name')) }}
            </h6>
        </div>
    </nav>
    <div class="m-0">
        @if ($action == 'edit')
            <div class="modal" id="#modal" style="display:block;background-color:rgba(0,0,0,0.8)">
                <div class="modal-dialog">
                    <div class="modal-content col-md-12">
                        <div class="modal-header p-1 align-items-center">
                            <h5 class="modal-text">Edit</h5>
                            @if (session('edit-success'))
                                <div class="alert alert-success">{{ session('edit-success') }}</div>
                            @elseif (session('edit-error'))
                                <div class="alert alert-danger">{{ session('edit-error') }}</div>
                            @endif
                            <a href="/dashboard" class="btn ml-auto" data-dismiss="modal">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        <div class="modal-body">
                            <form class="row" action="" method="POST" enctype="multipart/form-data">
                                <div class="col-md-7 p-0">

                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="from" class="form-label">Merchant</label>
                                        <select class="form-control" name="merchant">
                                            <option value="">--Select--</option>
                                            <option value="hotel" @if (old('merchant', $expdata->merchant) == 'hotel') selected @endif>
                                                Hotel
                                            </option>
                                            <option value="restaurant"@if (old('merchant', $expdata->merchant) == 'restaurant') selected @endif>
                                                Restaurant</option>
                                            <option value="hospital"@if (old('merchant', $expdata->merchant) == 'hospital') selected @endif>
                                                Hospital</option>
                                            <option value="rental car"@if (old('merchant', $expdata->merchant) == 'rental car') selected @endif>
                                                Rental Car</option>
                                            <option value="electronics"@if (old('merchant', $expdata->merchant) == 'electronics') selected @endif>
                                                Electronics
                                            </option>
                                            <option value="airline"@if (old('merchant', $expdata->merchant) == 'airline') selected @endif>
                                                Airline
                                            </option>
                                        </select>
                                        @error('merchant')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror

                                    </div>
                                    <div class="form-group ">
                                        <label for="from" class="form-label">Amount</label>
                                        <input type="number" value="{{ old('amount', $expdata->amount) }}" min="1"
                                            name="amount" max="1000000000" class="form-control" />
                                        @error('amount')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="from" class="form-label">Date</label>
                                        <input type="date" value="{{ old('date', $expdata->date) }}" name="date"
                                            class="form-control" />
                                        @error('date')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="from" class="form-label">Remark</label>
                                        <textarea class="form-control" name="remark" maxlength="1500" rows="4">{{ old('remark', $expdata->remark) }}</textarea>
                                        @error('remark')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group">
                                        <label for="receipt" class="btn form-label">Upload Receipt
                                            <input type="file" id="receipt" value="{{ old('receipt') }}" hidden
                                                name="receipt" class="form-control" />
                                        </label>
                                        @error('receipt')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="">
                                        <img hidden id="img" class="w-100" />
                                    </div> --}}
                                    <div class="form-group">
                                        <button type="submit" class="btn bg-color">
                                            Update
                                        </button>
                                    </div>

                                </div>
                                <div class="col-md-5">
                                    <div class="">
                                        <img src="{{ asset($expdata->receipt) }}" class="w-100" />
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="receipt" class="btn form-label">Change Receipt
                                            <input type="file" id="receipt" hidden name="receipt"
                                                class="form-control" />
                                        </label>
                                        @error('receipt')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="">
                                        <img hidden id="img" class="w-100" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <section class="p-0 expense-board">
            <div class="filter" id="filter">
                <div class="border-bottom d-flex align-items-center">
                    <p class="p-2">Filter</p>
                    <p class="mx-auto">
                        <a href="/dashboard">clear filter</a>
                    </p>
                    <span class="btn ml-auto" onclick="closeFilter()">
                        <i class="fa fa-times"></i>
                    </span>
                </div>
                <form class="mt-3" action="/filter">
                    <div class="form-group filter-item">
                        <label for="from">From</label>
                        <input type="date" name="from"
                            value="@if (isset($filterdata)) @if (isset($filterdata['from']))
                                {{ $filterdata['from'] }} @endif
                        @endif"
                            class="form-control" />
                    </div>
                    <div class="form-group filter-item">
                        <label for="from">To</label>
                        <input type="date" name="to"
                            value="@if (isset($filterdata)) @if (isset($filterdata['to'])) {{ $filterdata['to'] }} @endif
                        @endif"
                            class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="from" class="form-label">Merchant</label>
                        <select class="form-control" name="merchant">
                            <option value="">--Select--</option>
                            <option value="hotel" @if (old('merchant') == 'hotel') selected @endif>
                                Hotel
                            </option>
                            <option value="restaurant"@if (old('merchant') == 'restaurant') selected @endif>
                                Restaurant</option>
                            <option value="hospital"@if (old('merchant') == 'hospital') selected @endif>
                                Hospital</option>
                            <option value="rental car"@if (old('merchant') == 'rental car') selected @endif>
                                Rental Car</option>
                            <option value="electronics"@if (old('merchant') == 'electronics') selected @endif>
                                Electronics
                            </option>
                            <option value="airline"@if (old('merchant') == 'airline') selected @endif>
                                Airline
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn bg-color">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="board" id="board">
                <div class="exp-tbl">
                    <ul class="tbl-head sticky-top">
                        <li class="tbl-item">Date</li>
                        <li class="tbl-item">Merchant</li>
                        <li class="tbl-item">Amount</li>
                        <li class="tbl-item">Status</li>
                        <li class="tbl-item">Action</li>
                    </ul>
                    <div class="tbl-body w-100 scrollabl">
                        @foreach ($expenses as $key => $value)
                            <ul class="tbl-row">
                                <li class="tbl-item">{{ date('d-m-Y', strtotime($value->date)) }}</li>
                                <li class="tbl-item">{{ $value->merchant }}</li>
                                <li class="tbl-item">${{ $value->amount }}</li>
                                <li class="tbl-item">
                                    @if ($value->status == 'pending')
                                        <span class="badge badge-warning">pending</span>
                                    @elseif ($value->status == 'rejected')
                                        <span class="badge badge-danger">rejected</span>
                                    @elseif ($value->status == 'approved')
                                        <span class="badge badge-success">approved</span>
                                    @endif
                                </li>
                                <li class="tbl-item">
                                    <a href="/edit/{{ $value->exp_id }}" class="text-info p-1">
                                        <i class="fa fa-edit fa-lg"></i>
                                    </a>
                                    <form class="d-inline" action="/edit/{{ $value->exp_id }}" method="POST"
                                        onsubmit="return confirm('Are you sure to delete this expense')">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="border-0 text-danger p-1">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <section class="add-expense">
            <div class="p-2">
                <div class="border-bottom">
                    <p class="p-">Add Expense</p>
                </div>
                <form class="mt-3" action="/add-expense" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="from" class="form-label">Merchant</label>
                        <select class="form-control" name="merchant">
                            <option value="">--Select--</option>
                            <option value="hotel" @if (old('merchant') == 'hotel') selected @endif>Hotel</option>
                            <option value="restaurant"@if (old('merchant') == 'restaurant') selected @endif>Restaurant
                            </option>
                            <option value="hospital"@if (old('merchant') == 'hospital') selected @endif>Hospital
                            </option>
                            <option value="rental car"@if (old('merchant') == 'rental car') selected @endif>Rental Car
                            </option>
                            <option value="electronics"@if (old('merchant') == 'electronics') selected @endif>Electronics
                            </option>
                            <option value="airline"@if (old('merchant') == 'airline') selected @endif>Airline</option>
                        </select>
                        @error('merchant')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror

                    </div>
                    <div class="form-group ">
                        <label for="from" class="form-label">Amount</label>
                        <input type="number" value="{{ old('amount') }}" min="1" name="amount"
                            max="1000000000" class="form-control" />
                        @error('amount')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="from" class="form-label">Date</label>
                        <input type="date" value="{{ old('date') }}" name="date" class="form-control" />
                        @error('date')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="from" class="form-label">Remark</label>
                        <textarea class="form-control" name="remark" maxlength="1500" rows="4">{{ old('remark') }}</textarea>
                        @error('remark')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="receipt" class="btn form-label">Upload Receipt
                            <input type="file" id="receipt" value="{{ old('receipt') }}" hidden name="receipt"
                                class="form-control" />
                        </label>
                        @error('receipt')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    @if ($action == 'home')
                        <div class="">
                            <img hidden id="img" class="w-100" />
                        </div>
                    @endif
                    <div class="form-group">
                        <button type="submit" class="btn bg-color">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <script>
        const RECEIPT = document.querySelector("#receipt");
        RECEIPT.onchange = function() {
            if (this.files.length > 0) {
                let img = this.files[0];
                if (img.type.includes('image/')) {
                    const reader = new FileReader();

                    reader.addEventListener("load", () => {
                        // Base64 Data URL 👇
                        var imgFile = reader.result;
                        var imgTag = document.querySelector("#img");
                        imgTag.src = imgFile;
                        imgTag.hidden = false;
                    });
                    reader.readAsDataURL(img);
                }
            }
        }
    </script>
@endsection
