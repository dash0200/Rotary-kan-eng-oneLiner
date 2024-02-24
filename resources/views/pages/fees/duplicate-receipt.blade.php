<x-main-card>
    <h1>
      Duplicate Receipt
    </h1>
    <div class="w-full bg-gray-200" style="height: 1px;"></div>

    <div class="flex items-center justify-around border p-4 mt-6">

         <div>
             <x-label value="Student Name" />
             <x-input type="text" id="name" name="name" placeholder="Student Name" />
         </div>
        
         <div>
             <x-label value="Admission Year" />
             <select name="ac_year" id="year" class="w-full" required>
                 <option value="">Academic Year</option>
                 @foreach ($years as $year)
                     <option value="{{$year->id}}">{{ $year->year }}</option>
                 @endforeach
             </select> 
         </div>

       <x-button-primary value="GET" onclick="getByNameYear()" />
        
    </div>

    
    <div class="mt-10 flex justify-center pb-10">OR</div>

    <div class="flex justify-around border p-4">

        <div class="w-1/4">
            <x-label value="STS" />
            <x-input type="text" id="sts" placeholder="STS" />
            <x-button-primary value="GET" onclick="getBysts()" />
        </div>
        <div class="w-13">
            <x-label value="Registration ID" />
            <x-input type="text" id="id" placeholder="Register ID" />
            <x-button-primary value="GET" onclick="getById()" />
        </div>
        
    </div>

    <div class="p-4 border my-10 flex justify-around">
        <div>
             <x-label value="First Name" />
            <x-input type="text" placeholder="First Name" name="name" />
        </div>
        <div>
             <x-label value="Father Name" />
            <x-input type="text" placeholder="Father Name" name="fname" />
        </div>
        <div>
             <x-label value="Last Name" />
            <x-input type="text" placeholder="Last Name" name="lname" />
        </div>
        <div>
             <x-label value="DOB" />
            <x-input type="date" placeholder="DOB" name="dob" />
        </div>
        <x-button-primary value="GET" onclick="getByInfo()" />
    </div>

    <div>
        <x-table>
            <x-thead>
                <x-th>
                    Registration ID
                </x-th>
                <x-th>
                    STS
                </x-th>
                <x-th>
                    Name
                </x-th>
                <x-th>
                    Middle Name
                </x-th>
                <x-th>
                    Surname
                </x-th>
                <x-th>
                    DOB
                </x-th>
                <x-th>
                    
                </x-th>
            </x-thead>
            
            <tbody id="byId">
                
            </tbody>
        </x-table>
    </div>

</x-main-card>

<script>
    $("#year").select2();

    function getById() {
        $.ajax({
            type: "get",
            url: "{{route('trans.getByID')}}",
            data: {
                id: $("#id").val(),
            },
            dataType: "json",
            success: function (res) {
                $("#byId").html("")
                $("#byId").append(
                    `
                    <tr>
                    <x-td>
                        ${res.id}
                    </x-td>
                    <x-td>
                        ${res.sts}
                    </x-td>
                    <x-td>
                        ${res.name}
                    </x-td>
                    <x-td>
                        ${res.fname}
                    </x-td>
                    <x-td>
                        ${res.lname}
                    </x-td>
                    <x-td>
                        ${res.dob1}
                    </x-td>
                    <x-td>
                        <form action="{{route('fees.stdReceiptID')}}" method="post">
                            @csrf
                            <input type="text" name="id" value="${res.id}" hidden>
                            <x-button-primary value="Duplicate Receipt" />
                        </form>
                    </x-td>
                    </tr>
                        `
                )
            }
        });
    }

    function getBysts() {
        $.ajax({
            type: "get",
            url: "{{route('trans.getBysts')}}",
            data: {
                id: $("#sts").val(),
            },
            dataType: "json",
            success: function (res) {
                $("#byId").html('')
                $("#byId").append(
                    `
                    <tr>
                    <x-td>
                        ${res.id}
                    </x-td>
                    <x-td>
                        ${res.sts}
                    </x-td>
                    <x-td>
                        ${res.name}
                    </x-td>
                    <x-td>
                        ${res.fname}
                    </x-td>
                    <x-td>
                        ${res.lname}
                    </x-td>
                    <x-td>
                        ${res.dob1}
                    </x-td>
                    <x-td>
                        <form action="{{route('fees.stdReceiptID')}}" method="post">
                            @csrf
                            <input type="text" name="id" value="${res.id}" hidden>
                            <x-button-primary value="Duplicate Receipt" />
                        </form>
                    </x-td></tr>
                        `
                )
            }
        });
    }

    function getByNameYear() {
        $.ajax({
            type: "get",
            url: "{{route('trans.getByName')}}",
            data: {
                name: $("#name").val(),
                year: $("#year").val()
            },
            dataType: "json",
            success: function (res) {
                $("#byId").html('')
                for(let i=0; i<res.length; i++) {
                    $("#byId").append(
                    `
                    <tr>
                    <x-td>
                        ${res[i].id}
                    </x-td>
                    <x-td>
                        ${res[i].sts}
                    </x-td>
                    <x-td>
                        ${res[i].name}
                    </x-td>
                    <x-td>
                        ${res[i].fname}
                    </x-td>
                    <x-td>
                        ${res[i].lname}
                    </x-td>
                    <x-td>
                        ${res[i].dob1}
                    </x-td>
                    <x-td>
                        <form action="{{route('fees.stdReceiptID')}}" method="post">
                            @csrf
                            <input type="text" name="id" value="${res[i].id}" hidden>
                            <x-button-primary value="Duplicate Receipt" />
                        </form>
                    </x-td></tr>
                        `
                )
                }
            }
        });
    }
    
    function getByInfo() {

        $.ajax({
            type: "get",
            url: "{{route('trans.getByInfo')}}",
            data: {
                name : $("input[name='name']").val(),
                fname : $("input[name='fname']").val(),
                lname : $("input[name='lname']").val(),
                dob : $("input[name='dob']").val(),
            },
            dataType: "json",
            success: function (res) {
                $("#byId").html('')
                 (res);
                for(let i=0; i<res.length; i++) {
                    $("#byId").append(
                    `
                    <tr>
                    <x-td>
                        ${res[i].id}
                    </x-td>
                    <x-td>
                        ${res[i].sts}
                    </x-td>
                    <x-td>
                        ${res[i].name}
                    </x-td>
                    <x-td>
                        ${res[i].fname}
                    </x-td>
                    <x-td>
                        ${res[i].lname}
                    </x-td>
                    <x-td>
                        ${res[i].dob1}
                    </x-td>
                    <x-td>
                        <form action="{{route('fees.stdReceiptID')}}" method="post">
                            @csrf
                            <input type="text" name="id" value="${res[i].id}" hidden>
                            <x-button-primary value="Duplicate Receipt" />
                        </form>
                    </x-td></tr>
                        `
                )
                }
            }
        });
    }


</script>