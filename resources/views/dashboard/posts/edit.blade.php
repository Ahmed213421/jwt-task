<!-- Modal -->
<div class="modal fade" id="edit{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{$post->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel{{$post->id}}">{{ trans('dashboard.delete_confirm') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.posts.update', $post->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
            <div class="form-group mb-3">
                <label for="simpleinput">title </label>
                <input type="text" id="simpleinput" class="form-control" name="title" value="{{ $post->title }}">
            </div>
            <input type="hidden" name="admin_id" value="{{auth('admin')->user()->id}}">
            <div class="form-group mb-3">
                <label for="simpleinput">contact phone</label>
                <input type="text" id="simpleinput" class="form-control" name="contact_phone" value="{{$post->contact_phone}}">
            </div>
            <div class="form-group mb-3">
                <label for="simpleinput">{{ trans('dashboard.desc') }} </label>
                <textarea class="form-control" id="editor" style="height: 300px" name="description">

                    {{ old('description') ?? $post->description }}
                </textarea>

            </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('dashboard.close') }}</button>
                        <button type="submit" class="btn btn-danger">{{ trans('dashboard.edit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
