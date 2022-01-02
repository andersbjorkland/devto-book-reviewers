<ul class="cms-menu__list">
	<% loop $MainMenu %>
		<li class="$LinkingMode $FirstLast <% if $LinkingMode == 'link' %><% else %>opened<% end_if %>" id="Menu-$Code" title="$Title.ATT">
			<a href="$Link" $AttributesHTML>
				<% if $IconClass %>
					<span class="menu__icon $IconClass"></span>
				<% else_if $HasCSSIcon %>
					<span class="menu__icon icon icon-16 icon-{$Icon}">&nbsp;</span>
				<% else %>
					<span class="menu__icon font-icon-circle-star">&nbsp;</span>
				<% end_if %>
				<span class="text">$Title</span>
			</a>
		</li>
	<% end_loop %>

	<%-- Let's add a link to DEV here --%>
	<li>
		<a href="https://dev.to">
			<span class="menu__icon font-icon-circle-star">&nbsp;</span>
			<span class="text">DEV</span>
		</a>
	</li>
</ul>
