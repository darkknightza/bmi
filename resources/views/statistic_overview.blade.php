@extends('layout')
@section('style')

@endsection
@section('body')
    <div class="card">
        <div style="padding: 20px">
            <table id="myTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>ชื่อ-สกุล</th>
                    <th>ค่า BMI</th>
                    <th>สถานที่</th>
                    <th>ทำรายการครั้งล่าสุด</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(list,key) in overview_list">
                    <td>@{{ key+1 }}</td>
                    <td>@{{ list.Fname }} @{{ list.Lname }}</td>
                    <td>@{{ list.bmi }}</td>
                    <td>@{{ list.board_name }} @{{ list.site_name }}</td>
                    <td>@{{ list.time_update }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let app = new Vue({
            el: '#app',
            data: {
                overview_list: [],
            },
            created: function () {
                this.getLocation();
            },
            methods: {
                getLocation() {
                    let that = this;
                    axios.get('{{url('/get_static_overview')}}')
                        .then(function (response) {
                            if (response.data.success) {
                                that.overview_list =response.data.data;
                                setTimeout(function () {
                                    $('#myTable').DataTable();
                                }, 100);
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
