<div class="days_of_week">
	<label>{!! $field->label.($field->label_postfix != '' ? $field->label_postfix : '') !!}</label>
	<div class="day"><label for="{{{ $field->id }}}_1">Mon</label><input type="checkbox" name="{{{ $field->name }}}[]" value="M" id="{{{ $field->id }}}_1" {!! ($field->value['M'] ? 'checked="checked" ' : '' ) !!}/></div>
	<div class="day"><label for="{{{ $field->id }}}_2">Tue</label><input type="checkbox" name="{{{ $field->name }}}[]" value="T" id="{{{ $field->id }}}_2" {!! ($field->value['T'] ? 'checked="checked" ' : '' ) !!}/></div>
	<div class="day"><label for="{{{ $field->id }}}_3">Wed</label><input type="checkbox" name="{{{ $field->name }}}[]" value="W" id="{{{ $field->id }}}_3" {!! ($field->value['W'] ? 'checked="checked" ' : '' ) !!}/></div>
	<div class="day"><label for="{{{ $field->id }}}_4">Thu</label><input type="checkbox" name="{{{ $field->name }}}[]" value="R" id="{{{ $field->id }}}_4" {!! ($field->value['R'] ? 'checked="checked" ' : '' ) !!}/></div>
	<div class="day"><label for="{{{ $field->id }}}_5">Fri</label><input type="checkbox" name="{{{ $field->name }}}[]" value="F" id="{{{ $field->id }}}_5" {!! ($field->value['F'] ? 'checked="checked" ' : '' ) !!}/></div>
	<div class="day"><label for="{{{ $field->id }}}_6">Sat</label><input type="checkbox" name="{{{ $field->name }}}[]" value="S" id="{{{ $field->id }}}_6" {!! ($field->value['S'] ? 'checked="checked" ' : '' ) !!}/></div>
	<div class="day"><label for="{{{ $field->id }}}_7">Sun</label><input type="checkbox" name="{{{ $field->name }}}[]" value="U" id="{{{ $field->id }}}_7" {!! ($field->value['U'] ? 'checked="checked" ' : '' ) !!}/></div>
	@include('form-maker::pieces.note')
</div>
