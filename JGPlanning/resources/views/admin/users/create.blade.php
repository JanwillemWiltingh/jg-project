@extends('layouts.app')

@section('content')
    <h1>Create A New User</h1>
    <form>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="text" name="name" placeholder="Name">
        </label>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="email" name="email" placeholder="Email">
        </label>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="password" name="password" placeholder="Password">
        </label>
        <label>
            <input style="width: 300px; height: 35px; font-size: 20px;" type="password" name="confirmPassword" placeholder="Confirm Password">
        </label><hr>
        <label style="color: black; font-size: 20px;">What role does the user get?</label>
        <select name="roles" style="display: block; width: 100px;">
            <option name="userRole" selected>User</option>
            <option name="adminRole">Admin</option>
        </select><br>
        <input style="display: block;" type="submit" name="createUser" value="Create">
        @endsection
    </form>
