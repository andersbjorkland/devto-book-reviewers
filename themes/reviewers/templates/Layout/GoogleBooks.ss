<section class="container">
    <h1 class="text-center">Review a book</h1>
    
    <% include SearchBar %>

    <div id="Content" class="searchResults">

        <% if $Books %>
            <p class="searchQuery">Results for "$Query"</p>
            <ul id="SearchResults">

                <% loop $Books %>
                    <li>
                        <h4>
                            $title
                        </h4>
                        <div>
                            <% loop $authors %>
                                <p>$AuthorName</p>
                            <% end_loop %>
                        </div>
                 
                        <a class="reviewLink" href="/review/book/{$volumeId}" title="Review &quot;{$title}&quot;">Review &quot;{$title}&quot;...</a>
                    </li>
                <% end_loop %>

            </ul>
            <div id="PageNumbers">
                <div class="pagination">

                    <% loop $Pagination %>
                        <span>
                        
                            <% if $start.link %>
                                <a class="go-to-page" href="$start.link">|<</a>
                            <% end_if %>

                            <% if $previous.link %>
                                <a class="go-to-page" href="$previous.link"><</a>
                            <% end_if %>

                            <% loop $pages %>
                                <% if $currentPage %>
                                    <strong><a class="go-to-page" href="$link">$page</a></strong>
                                <% else %>
                                    <a class="go-to-page" href="$link">$page</a>
                                <% end_if %>
                            <% end_loop %>

                            <% if $next.link %>
                                <a class="go-to-page" href="$next.link">></a>
                            <% end_if %>
                            
                        </span>
                    <% end_loop %>
                    
                </div>
            </div>
        <% end_if %>
    </div>
</section>


