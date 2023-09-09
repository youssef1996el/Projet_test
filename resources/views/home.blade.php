@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center text-primary text-uppercase">Add User</h2>
            </div>
            <table class="text-center mx-auto">
                <form  action="{{url('StoreUsers')}}" method="post">
                    @csrf
                    <tr>
                        <td>
                            <label for="">Name :</label>
                        </td>
                        <td>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  name="name" placeholder="Entre your name">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="">Email :</label>
                        </td>
                        <td>
                            <input type="Email" class="form-control @error('email') is-invalid @enderror"  name="email" placeholder="Entre your email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="">Password :</label>
                        </td>
                        <td>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"  name="password" placeholder="Entre your password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn btn-success text-uppercase mt-2" type="submit">Save User</button>
                        </td>
                    </tr>
                </form>
            </table>

        </div>
        <div class="col-md-8 mt-5">
            <div class="card">
                <table class="table table-bordered p-5" id="TableUser">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <Th>Email</Th>
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
{{-- Modal update user --}}
<div class="modal" tabindex="-1"  id="modalEditUser" >
    <div class="modal-dialog modal-md" >
        <div class="modal-content bg-light" >
            <div class="modal-header">
                <h4>UDAPTE USER <span id="nameUser"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="ListError"></ul>
                <div class="form-group">
                    <label for="">Name :</label>
                    <input type="text" id="Name" class="form-control">
                    <label for="">Email :</label>
                    <input type="email" id="Email" class="form-control">
                    <label for="">Password :</label>
                    <input type="password" id="Password" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="button" id="btnUpdateUser">Save</button>
                <button class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Férme</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        function getUser()
        {
            $.ajax({
                type: "get",
                url: "{{url('GetUsers')}}",
                dataType: "json",
                success: function (response)
                {
                    if(response.statut == 200)
                    {

                        $('#TableUser').find('tbody').html('');
                        $.each(response.data, function (index, value)
                        {

                            $('#TableUser').find('tbody').append('<tr>\
                                                                        <td>'+value.name+'</td>\
                                                                        <td>'+value.email+'</td>\
                                                                        <td>\
                                                                            <i class="fa-solid fa-pen-to-square editUser" value='+value.id+' title="Update User"></i>\
                                                                            <i class="fa-solid fa-trash trashUser" value='+value.id+' title="Delete user"></i>\
                                                                        </td>\
                                                        </tr>');
                        });
                    }
                }
            });
        }
        getUser();

        $('#btnSaveUser').on('click',function()
        {
            let name = $('.NameUser').val();
            let email = $('.EmailUser').val();
            let password = $('.PasswordUser').val();
            $.ajax({
                type: "post",
                url: "{{url('StoreUsers')}}",
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data:
                {
                    name : name,
                    email : email,
                    password:password,
                },
                dataType: "json",
                success: function (response) {
                    if(response.statut == 200)
                    {
                        $('.NameUser').val('');
                        $('.EmailUser').val('');
                        $('.PasswordUser').val('');
                        getUser();
                        Swal.fire(
                            'Good job!',
                            'you add successfully',
                            'success'
                            )
                    }
                    else if(response.statut == 400)
                    {

                    }
                }
            });
        });
        var id =null;
        $('#TableUser tbody').on('click','.editUser',function()
        {
            let name    = $(this).closest('tr').find('td:eq(0)').text();
            let email   = $(this).closest('tr').find('td:eq(1)').text();
            id          = $(this).attr('value');
            $('#modalEditUser').modal('show');
            $('#Name').val(name);
            $('#Email').val(email);
        });

        $('#btnUpdateUser').on('click',function()
        {
            $.ajax({
                type: "post",
                url: "{{url('updateUser')}}",
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data:
                {
                    name        : $('#Name').val(),
                    email       : $('#Email').val(),
                    password    : $('#Password').val(),
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
                            $('#modalEditUser').modal('hide');
                            getUser();
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
        $('#TableUser tbody').on('click','.trashUser',function()
        {

            // check user is existing have any post
            Swal.fire({
                title: 'Do you want to detele this user',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Save',
                denyButtonText: `Don't save`,
            }).then((result) => {

                if (result.isConfirmed)
                {
                    var id = $(this).attr('value');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                            type: "get",
                            url: "{{url('checkUser')}}",
                            data:
                            {
                                iduser : $(this).attr('value'),
                            },
                            dataType: "json",
                            success: function (response)
                                {
                                    if(response.statut == 400)
                                    {
                                        Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'This user have post',
                                        })
                                         return 0;
                                    }
                                    else
                                    {
                                        $.post("{{ url('DeleteUser') }}", {
                                            _token: $('meta[name="csrf-token"]').attr('content'),
                                            id: id
                                            }, function (response, textStatus, jqXHR) {
                                                if(response.statut == 200)
                                                {
                                                     Swal.fire(
                                                        'Good job!',
                                                        'your deleted successfully',
                                                        'success'
                                                    )
                                                    getUser();
                                                }
                                            },
                                            "json"
                                        );
                                    }
                                }
                    });

                } else if (result.isDenied)
                {

                    Swal.fire('Changes are not saved', '', 'info')
                    return 0;
                }
            })

        });


    });
</script>

<style>
    .editUser
    {
        color:green;
        font-size: 18px;
        cursor: pointer;
    }
    .trashUser
    {
        color:red;
        font-size: 18px;
        cursor: pointer;
    }
</style>
@endsection
