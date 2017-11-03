<table class="{{ $Table->getClassesString() }}">
    <thead>
        <tr>
        @foreach($Table->display_fields as $field_name)
            <th>{{ $Table->getLabel($field_name) }}</th>
        @endforeach
        </tr>
    </thead>
    <tobdy>
        @foreach($Table->data as $row)
        <tr>
            @foreach($Table->display_fields as $field_name)
                <td>{{ $row[$field_name] }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
