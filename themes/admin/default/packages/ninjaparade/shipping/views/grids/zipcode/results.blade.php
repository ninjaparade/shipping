<script type="text/template" data-grid="zipcode" data-template="results">

	<% _.each(results, function(r) { %>

		<tr>
			<td><input content="id" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="{{ URL::toAdmin('shipping/zipcodes/<%= r.id %>/edit') }}"><%= r.id %></a></td>
			<td><%= r.zip %></td>
			<td><%= r.city %></td>
			<td><%= r.state %></td>
			<td><%= r.country %></td>
			<td><%= r.local %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
