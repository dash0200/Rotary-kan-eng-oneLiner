<x-main-card>
    
    <div class="flex justify-center w-full">
        <div class="flex flex-col items-center pb-10">
            <img class="mb-3 w-24 h-24" src="{{asset('logo.png')}}" alt="Bonnie image">
            <span>Total Number of Registered Students at the School</span>
            <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{$students}}</h5>
        </div>
    </div>

    
        <div class ='flex justify-center'>
             For the Academic Year : <b>{{$year}}</b>
        </div>

        TOTAL NUMBER OF STUDENTS STUDYING IN THIS YEAR
    <div class="flex justify-around flex-wrap ">
        @foreach($studentCounts as $data)
        <div class="flex flex-col items-center pb-10">
    
            <span class="uppercase">
            {{$data['name']}}
            </span>
            <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{$data['count']}}</h5>
        </div>
        @endforeach
    </div>

    <div class="flex flex-col items-center">
        <div>Total Students Studying this Year:

        <div class="flex justify-center font-bold">
        {{$totalStudentThisYear - $newAdmission}} + {{$newAdmission}} = {{$totalStudentThisYear}}
        </div>
    </div>

</x-main-card>
