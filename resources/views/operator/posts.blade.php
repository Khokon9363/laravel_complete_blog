@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <a href="{{ url('/operator/dashboard') }}">{{ __('Operator Dashboard') }}</a>
                |
                <a href="{{ url('/posts') }}">{{ __('Posts') }}</a>
                </div>

                <div class="card-body">
                @if ($errors->any())
                    <div class="float-left alert text-center alert-light">
                        <ol>
                            @foreach ($errors->all() as $error)
                                <li class="text-danger">{{ $error }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
                @if(session('success'))
                    <div class="float-left alert text-center alert-success">
                        <div class="text-success">{{ session('success') }}</div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="float-left alert text-center alert-error">
                        <div class="text-danger">{{ session('error') }}</div>
                    </div>
                @endif
                    <div class="float-right mb-3">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPost">Add Post</button>
                    </div>
                    <table class="table text-center table-stripe table-bordered">
                        <tr>
                            <th>SL</th>
                            <th>Post</th>
                            <th>Image</th>
                            <th>Comment (s)</th>
                            <th>Publication status</th>
                            <th>Action</th>
                        </tr>
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Str::words($post->post, 5) }}</td>
                            <td><img src="{{ asset('/post_images/'.$post->image) }}" alt="Post image" style="height: 60px; width: auto;"></td>
                            <td>{{ ($post->comments) ? number_format(count($post->comments->where('status', 1))) : number_format(0) }}</td>
                            <td><div class="badge badge-pill {{ ($post->status === 0) ? 'badge-danger' : 'badge-success' }}">{{ ($post->status === 0) ? 'Inactive' : 'Active' }}</div></td>
                            <td>
                                @if($post->user->role != 2)
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editPost{{ $post->id }}">Edit</button>
                                @else
                                    {{ 'N/A' }}
                                @endif
                                @if($post->user->role != 2)
                                    <a href="{{ url('/delete/post/'.$post->id) }}" class="btn btn-danger btn-sm">Danger</a>
                                @endif
                                <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#comment{{ $post->id }}">Comment</button>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="addPost" tabindex="-1" role="dialog" aria-labelledby="addPostTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPostTitle">Add a post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('/store/post') }}" method="post" enctype="multipart/form-data">
                    @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="post">Post</label>
                                <textarea name="post" id="post" rows="10" class="form-control" placeholder="Write your post here"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Publication status</label> <br>
                                <input type="radio" checked value="1" name="status" id="active">
                                <label for="active">Active</label>
                                <input type="radio" name="status" value="0" id="inactive">
                                <label for="inactive">Inactive</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Publish Post</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            @foreach($posts as $post)
            <div class="modal fade" id="editPost{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="editPostTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPostTitle">Edit the post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('/update/post/'.$post->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="post">Post</label>
                                <textarea name="post" id="post" rows="10" class="form-control" placeholder="Write your post here">{{ $post->post }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <img src="{{ asset('/post_images/'.$post->image) }}" alt="Post image" style="height: 60px; width: auto;">
                                <input type="file" name="image" id="image" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Publication status</label> <br>
                                <input type="radio" {{ ($post->status === 1) ? 'checked' : '' }} value="1" name="status" id="active">
                                <label for="active">Active</label>
                                <input type="radio" {{ ($post->status === 0) ? 'checked' : '' }} name="status" value="0" id="inactive">
                                <label for="inactive">Inactive</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Post</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            @endforeach

            @foreach($posts as $post)
            <div class="modal fade" id="comment{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="commentTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentTitle">Comment on the post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('/store/comment/'.$post->id) }}" method="post">
                    @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="comment">Comment</label>
                                <textarea name="comment" id="comment" rows="6" class="form-control" placeholder="Write your comment here"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Comment</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
@endsection