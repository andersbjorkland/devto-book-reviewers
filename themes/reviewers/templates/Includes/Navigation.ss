<nav class="primary">
	<span class="nav-open-button">²</span>
	<ul>
		<% loop $Menu(1) %>
			<% if $MenuTitle.XML == "Registration" %>
				<% if not $CurrentMember %>
					<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
				<% end_if %>
			<% else %>
				<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
			<% end_if %>
		<% end_loop %>
		
		<%-- Add our new 'Book' link here --%>
		<li class="<% if $URLSegment == "App\Controller\BookController" %>current<% end_if %>"><a href="/book" title="Books">Books</a></li>
		
		<% if $CurrentMember %>
			<li class="<% if $URLSegment == "App\Controller\ReviewController" %>current<% end_if %>"><a href="/review" title="Review">Review</a></li>
			<li><a href="$AdminURL" title="Admin">Admin</a></li>
			<li><a href="/Security/logout?BackURL=/" title="Logout">Logout</a></li>	
		<% else %>
			<li><a href="/Security/login?BackURL=/" title="Login">Login</a></li>	
		<% end_if %>
		
	</ul>
</nav>
