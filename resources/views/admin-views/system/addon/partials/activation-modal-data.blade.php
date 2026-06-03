<form action="{{ route('admin.business-settings.addon.activation') }}" method="post">
    @csrf
    <input type="hidden" name="path" value="{{ $path }}">
    <input type="hidden" name="username" value="activated">
    <input type="hidden" name="purchase_code" value="activated">
    <div class="modal-header border-0 pb-0 d-flex justify-content-end">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body px-5 px-sm-4">
        <div class="d-flex flex-column align-items-center text-center gap-2 mb-2">
            <h5 class="modal-title">{{ translate('messages.Activate') }} {{ $addon_name }}</h5>
            <p class="text-muted mb-0">{{ translate('messages.This addon will be enabled for this customized build.') }}</p>
        </div>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="button" class="btn btn-light min-w-120px" data-dismiss="modal">{{ translate('messages.cancel') }}</button>
            <button type="submit" class="btn btn--primary min-w-120px">{{ translate('messages.Activate') }}</button>
        </div>
    </div>
</form>