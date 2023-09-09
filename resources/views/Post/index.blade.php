@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title text-center text-primary text-uppercase">Add Post</h2>
                </div>
                <table class="text-center mx-auto">
                    <form  action="{{url('StorePost')}}" method="post">
                        @csrf
                        <tr>
                            <td>
                                <label for="">title :</label>
                            </td>
                            <td>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"  name="title" placeholder="Entre your title">
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="">Content :</label>
                            </td>
                            <td>
                                <input type="text" class="form-control @error('content') is-invalid @enderror"  name="content" placeholder="Entre your content">
                                @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="">Users:</label>
                            </td>
                            <td>
                                <select name="iduser" id="" class="form-select">
                                    @foreach ($users as $item)
                                        <option value=""></option>
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('user')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="btn btn-success text-uppercase mt-2" type="submit">Save Post</button>
                            </td>
                        </tr>
                    </form>
                </table>

            </div>
            <div class="col-md-8 mt-5">
                <div class="card">
                    <table class="table table-bordered p-5" id="TablePost">
                        <thead>
                            <tr>
                                <th>title</th>
                                <Th>Content</Th>
                                <th>Users</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal update Post --}}
<div class="modal" tabindex="-1"  id="modalEditPost" >
    <div class="modal-dialog modal-md" >
        <div class="modal-content bg-light" >
            <div class="modal-header">
                <h4>UDAPTE Post <span id="nameUser"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="ListError"></ul>
                <div class="form-group">
                    <label for="">title :</label>
                    <input type="text" id="title" class="form-control">
                    <label for="">content :</label>
                    <input type="email" id="content" class="form-control">
                    <label for="">users :</label>
                    <select  id="iduser" class="form-select">
                        @foreach ($users as $item)
                            <option value=""></option>
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="button" id="btnUpdatePost">Save</button>
                <button class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Férme</button>
            </div>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function () {
            function getPost()
            {
                $.ajax({
                    type: "get",
                    url: "{{url('getPost')}}",
                    dataType: "json",
                    success: function (response)
                    {
                        if (response.statut == 200)
                        {
                            $('#TablePost').find('tbody').html('');
                            $.each(response.data, function (index, value)
                            {
                                $('#TablePost').find('tbody').append('<tr>\
                                                                        <td>'+value.title+'</td>\
                                                                        <td>'+value.content+'</td>\
                                                                        <td>'+value.name+'</td>\
                                                                        <td>\
                                                                            <i class="fa-solid fa-pen-to-square editPost" value='+value.id+' title="Update Post"></i>\
                                                                            <i class="fa-solid fa-trash trashPost" value='+value.id+' title="Delete Post"></i>\
                                                                        </td>\
                                                                        </tr>')
                            });
                        }
                    }
                });
            }
            getPost();
            var id =null;
            $('#TablePost tbody').on('click','.editPost',function()
            {
                let title   = $(this).closest('tr').find('td:eq(0)').text();
                let content   = $(this).closest('tr').find('td:eq(1)').text();
                id          = $(this).attr('value');
                $('#modalEditPost').modal('show');
                $('#title').val(title);
                $('#content').val(content);
            });
            $('#btnUpdatePost').on('click',function()
            {
                $.ajax({
                    type: "post",
                    url: "{{url('updatePost')}}",
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    data:
                    {
                        title        : $('#title').val(),
                        content       : $('#content').val(),
                        iduser      : $('#iduser').val(),
                        id          : id,
                    },
                    dataType: "json",
                    success: function (response)
                    {
                        if(response.statut == 200)
                        {
                            Swal.fire(
                                'Good job!',
                                'you update successfully',
                                'success'
                                )
                                $('#modalEditPost').modal('hide');
                                getPost();
                        }
                        else if (response.statut == 400)
                        {
                            $('#ListError').html("");
                            $('#ListError').addClass('alert alert-danger');
                            $.each(response.errors, function (key, value) {
                                $('#ListError').append('<li>' + value + '</li>');
                            });
                        }
                    }
                });
            });
            $('#TablePost tbody').on('click','.trashPost',function()
            {
                Swal.fire({
                    title: 'Do you want to delete this post ?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    denyButtonText: `Don't save`,
                    }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed)
                        {
                            $.ajax({
                                type: "post",
                                url: "{{url('DeletePost')}}",
                                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                                data:
                                {
                                    id : $(this).attr('value'),
                                },
                                dataType: "json",
                                success: function (response)
                                {
                                    if(response.statut == 200)
                                    {
                                        Swal.fire(
                                            'Good job!',
                                            'you delete successfully',
                                            'success'
                                            )
                                        getPost();
                                    }
                                }
                            });

                        } else if (result.isDenied) {

                        }
                    })
            });
        });
    </script>
    <style>
        .editPost
        {
            color:green;
            font-size: 18px;
            cursor: pointer;
        }
        .trashPost
        {
            color:red;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
@endsection
