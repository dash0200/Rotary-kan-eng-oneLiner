<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>STUDY CERTIFICATE</title>
    <style>
        body {
            border: 1px solid black;            
        }

        .fb {
            font-weight: bold;
        }

        .bb {
            border-bottom: 1px solid black;
        }

        td {
            padding-top: 0.5rem; padding-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    
    <table style="width: 100%;" class="fb">
        <tr>
            <td align="center" style="font-size: 14px;">
               <div style="margin-bottom: 0.5rem;">
                NAVANAGAR ROTARY EDUCATION SOCIETY'S
               </div>
            </td>
        </tr>
    </table>

    <table style="width: 100%;" class="bb fb">
        <tr>
            <td align="center" style="font-size: 18px; width: 100%;">
                NAVANAGAR ROTARY ENGLISH MEDIUM SCHOOL
            </td>
        </tr>
        <tr>
            <td align="center" style="width: 40%;">
                NAVANAGAR, HUBBALLI-580025
            </td>
        </tr>
    </table>

    <table style="width: 100%;" class="bb fb">
        <tr>
            <td align="center">
                <div style="font-size: 20px; padding: 0.3rem;">
                    STUDY CERTIFICATE
                </div>
            </td>
        </tr>
    </table>

    <table style="width: 100%;" class="fb">
        <tr>
            <td align="center">
                THIS IS TO CERTIFY THAT
            </td>
        </tr>
    </table>

    <table style="width: 100%; padding-left: 0.5rem; margin-top: 0.5rem;">
        <tr>
            <td align="left" style="width: 30%;">
                <span >Kumar / Kumari. </span>
            </td>

            <td align="left" style="width: 100%;" class="bb fb">
              {{strtoupper($student->name)}}
            </td>
        </tr>
    </table>

    <table style="width: 100%; padding-left: 0.5rem; margin-top: 0.5rem;">
        <tr>
            <td align="left" style="width: 30%;">
                <span>Son/Daughter of  </span>
            </td>

            <td align="left" style="width: 100%;" class="bb fb">
              {{$student->fname}}
            </td>
        </tr>
    </table>

    <table style="width: 100%; padding-left: 0.5rem; margin-top: 0.5rem;">
        <tr>
            <td>
                is a student of our school.
            </td>
        </tr>
    </table>

    <table style="width: 100%; padding-left: 0.5rem; margin-top: 0.5rem;">
        <tr>
            <td >
                <span>He/She Studied from</span>
            </td>

            <td style="width: 10%;">
              <u class="fb">{{$study->from_stdy}}</u>              
            </td>

            <td style="width: 10%">to</td>

            <td>
                <u class="fb" >{{$study->to_stdy}}</u>
            </td>

            <td style="width: 40%" align="left">
                Standard in our Instituition.
            </td>
        </tr>
    </table>

    <table style="width: 100%; padding-left: 0.5rem; margin-top: 0.5rem;">
        <tr>
            <td style="width: 40%;">
                <span>During the academic year from </span>
            </td>

            <td align="left" style="width: 10%;">
              <u class="fb">{{$study->from_year}}</u>              
            </td>

            <td>to  <u style="margin-left: 1rem;" class="fb">{{$study->to_year}}</u></td>
        </tr>
    </table>

    <table style="width: 100%; padding-left: 0.5rem; margin-top: 0.5rem;">
        <tr>
            <td style="width: 40%;">
                <span>Him/Her Registration Number is: </span>
            </td>

            <td align="center" class="bb fb">
              {{$student->id}}              
            </td>
            <td>
                and He/She belongs to caste
            </td>
        </tr>
    </table>

    <table style="width: 100%; padding-left: 0.5rem; margin-top: 1rem;  padding-bottom: 0.6rem;" class="bb">
        <tr>
            <td align="center">
                The above details are true and correct to the best of my knowledge.
            </td>
        </tr>
    </table>

    <table style="width: 100%; padding-left: 0.5rem; margin-top: 5rem;  padding-bottom: 0.6rem;">
        <tr>
            <td align="left" style="padding-top: 0.7rem; padding-bottom: 0.7rem">
                Date : {{date("d-m-Y")}}
            </td>
        </tr>

        <tr>
            <td>
                Place : Navanagar, Hubballi.
            </td>
        </tr>
    </table>


</body>
</html>