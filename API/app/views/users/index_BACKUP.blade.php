@extends('template.framework_datatable_base')
@section('dt_header')
    @parent
    @foreach ($cols_friendly as $col)
        @unless ($col === 'F_FLAGGED')
            @if ($col === 'ID')
                <th class="gridedit-disabled">{{ $col }}</th>
            @else
                <th>{{ $col }}</th>
            @endif
        @else
            <th class="flag gridedit-disabled"><i class="fa fa-flag"></i></th>
            <script>$('#bar_flag').show()</script>
        @endunless
    @endforeach
@stop

@section('dt_rows')
    @unless (count($data))
        @parent
    @else
        @for ($row = 0; $row < count($data); $row++)
            <tr>
                <td><input type="checkbox" class="toggle_one" /></td>
                
                @foreach ($data[$row] as $k => $v)
                    @unless ($k === 'F_FLAGGED')
                        <td>{{{ $v }}}</td> 
                    @else 
                        @if ($v == 0)
                            <td>&nbsp;</td>
                        @else
                            <td><i class="fa fa-flag"></i></td>
                        @endif
                    @endunless
                @endforeach
            </tr>
        @endfor
    @endunless
@stop