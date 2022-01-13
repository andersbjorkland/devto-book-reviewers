<section class="container">
    <h1 class="text-center">Books</h1>
    
    <% include SearchBar %>

    <div id="Content" class="searchResults">

        <% if $Books %>
            <% if $Query %>
                <p class="searchQuery">Results for "$Query"</p>
            <% end_if %>
            <ul id="SearchResults">

                <% loop $Books %>
                    <li>
                        <h4>
                            <a class="reviewsLink" href="/book/view/{$VolumeID}" title="&quot;{$title}&quot;">$Title</a>
                        </h4>
                        <div>
                            <p>
                                by 
                                <% loop $Authors %>
                                    $Name<% if not $IsLast %><% if $FromEnd == 2 %> and <% else %>, <% end_if %><% end_if %>
                                <% end_loop %>
                            </p>
                            <p>$AverageRatingStars ($Reviews.Count)</p>
                        </div>                 
                    </li>
                <% end_loop %>

            </ul>
            <% if $Books.MoreThanOnePage %>
                <% if $Books.NotFirstPage %>
                    <a class="prev" href="$Books.PrevLink">Prev</a>
                <% end_if %>
                <% loop $Books.PaginationSummary %>
                    <% if $CurrentBool %>
                        $PageNum
                    <% else %>
                        <% if $Link %>
                            <a href="$Link">$PageNum</a>
                        <% else %>
                            ...
                        <% end_if %>
                    <% end_if %>
                <% end_loop %>
                <% if $Books.NotLastPage %>
                    <a class="next" href="$Books.NextLink">Next</a>
                <% end_if %>
            <% end_if %>
        <% end_if %>
    </div>
</section>