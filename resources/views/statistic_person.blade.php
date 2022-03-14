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
                    <th>น้ำหนัก</th>
                    <th>ส่วนสูง</th>
                    <th>ค่า BMI</th>
                    <th>มาตราฐาน</th>
                    <th>เวลา</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(list,key) in user_bmi">
                    <td>@{{ key+1 }}</td>
                    <td>@{{ list.weight }}</td>
                    <td>@{{ list.height }}</td>
                    <td>@{{ list.bmi }}</td>
                    <td>@{{ list.bmi_criterion }}</td>
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
