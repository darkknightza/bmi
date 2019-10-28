@extends('layout')
@section('style')

@endsection
@section('body')
    <example-component></example-component>
@endsection
@section('script')
    <script>
        let app = new Vue({
            el: '#app',
            data: {
                user_bmi: [],
            },
            created: function () {
                this.getLocation();
            },
            methods: {
                getLocation() {
                    let that = this;
                    axios.get('{{url('/get_bmi_user')}}')
                        .then(function (response) {
                            if (response.data.success) {
                                that.user_bmi =response.data.data;
                                setTimeout(function () {
                                    $('#myTable').DataTable();
                                }, 100);
                            }else{
                                $('#myTable').DataTable();
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
            },
        });
    </script>
@endsection
