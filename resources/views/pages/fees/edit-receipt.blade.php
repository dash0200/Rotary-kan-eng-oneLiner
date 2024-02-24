<x-main-card>
    Edit Fee Receipt
    <form action="{{ route('fees.updateRecipt') }}" method="get">
        <div class="w-full flex justify-around bg-gray-200" style="height: 1px;"></div>
        <div class="mt-5">
            <select name="" id="stdsearh" class="w-full">
                <option value="">Start Typing [ STS - Register_No, Name Father_Name Last_Name, (date_of_admission) ]</option>
            </select>
        </div>
    
        <div class="mt-5">
            <input type="text" name="student" id="student" hidden/>
        </div>
        
        <div class="mt-5">
            <label for="class">Class</label>
            <select name="class" id="class" class="w-full">
                @foreach ($calsses as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
    
        <div class="mt-5">
            <label for="year">Year</label>
            <select name="year" id="year" class="w-full">
                @foreach ($years as $year)
                    <option value="{{ $year->id }}" @if ($year->year == '2024-25')
                        selected
                    @endif>{{ $year->year }}</option>
                @endforeach
            </select>
        </div>

        <x-button-primary>Submit</x-button-primary>
    </form>
   
</x-main-card>

<script>
    $('#class').select2()
    $('#year').select2()
        
    $("#stdsearh").select2({
        ajax: {
            url: "{{route('getStdId')}}",
            type: "get",
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });



    $("#stdsearh").on("select2:select", function(e) {
        let data = e.params.data;
        $.ajax({
            type: "get",
            url: "{{route('getstudent')}}",
            data: {
                id: data.id
            },
            dataType: "json",
            success: function(res) {
               $('#student').val(res[0].id)
            }
        });
    })

</script>