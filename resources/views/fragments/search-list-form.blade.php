<form method="get" class="form-inline">
    <div class="input-group">
        <input type="text" class="form-control" name="search" value="{{ Request::input('search') }}" placeholder="Search">
        <div class="input-group-btn">
            <button class="btn btn-default" type="submit" style="padding:9px 15px">
            <i class="fas fa-search"></i>
            </button>
        </div>
        <input type="hidden" name="page" value="1">
        <input type="hidden" name="sortBy" value="{{ Request::input('sortBy') ?: $default_field }}">
        <input type="hidden" name="sortOrder" value="{{ Request::input('sortOrder') ?: 'ASC' }}">
    </div>
</form>