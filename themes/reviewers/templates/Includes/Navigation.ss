<nav class="primary">
	<span class="nav-open-button">²</span>
	<ul>
		<% loop $Menu(1) %>
			<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
		<% end_loop %>
		<li class="<% if $URLSegment == "App\Controller\ReviewController" %>current<% end_if %>"><a href="/review" title="Review">Review</a></li>
		<li><a href="$AdminURL" title="Admin">Admin</a></li>
	</ul>
</nav>
