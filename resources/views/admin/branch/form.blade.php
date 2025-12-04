<div class="card-body card-block">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="name" class=" form-control-label">Branch Name <span class="text-danger">*</span></label>
                <input value="{{ old('name', $user->name ?? '') }}" type="text" id="name" name="name" placeholder="Full Name" class="form-control form-control-sm">
                <span style="color: red"> {{ $errors->first('name') }} </span>
                
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="phone" class=" form-control-label">Phone No </label>
                <input value="{{ old('phone', $user->phone ?? '') }}" type="text" name="phone" id="phone" placeholder="Phone No" class="form-control form-control-sm">
                <span style="color: red">
                    {{ $errors->first('phone') }}
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="email" class=" form-control-label">Email</label>
                <input value="{{ old('email', $user->email ?? '') }}" type="text" id="email" name="email" placeholder="Email" class="form-control form-control-sm">
                <span style="color: red">
                    {{ $errors->first('email') }}
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="address" class=" form-control-label">Address</label>
                <input name="address" value="{{ old('address', $user->address ?? '') }}" type="text" placeholder="address" class="form-control form-control-sm">
                <span style="color: red">
                    {{ $errors->first('address') }}
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="details" class=" form-control-label">Details</label>
                <input name="details" value="{{ old('details', $user->details ?? '') }}" type="text" placeholder="details" class="form-control form-control-sm">
                <span style="color: red">
                    {{ $errors->first('details') }}
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="remarks" class=" form-control-label">remarks</label>
                <input name="remarks" value="{{ old('remarks', $user->remarks ?? '') }}" type="text" placeholder="remarks" class="form-control form-control-sm">
                <span style="color: red">
                    {{ $errors->first('remarks') }}
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 mb-5">
            <div class="form-group row">
                <div class="col-lg-6 mb-5">
                    <label class=" form-control-label">Logo <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <label></label>
                        <div></div>
                        <div class="custom-file">
                            <input type="file" name="pro_pic" class="custom-file-input" id="customFile" />
                            <label class="custom-file-label" for="customFile">Select File</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <button type="submit" class="btn btn-success mr-2">Save</button>
        </div>
    </div>
</div>