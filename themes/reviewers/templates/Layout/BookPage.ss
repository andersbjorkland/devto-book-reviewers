<section class="container">
    <h1 class="text-center">$Book.Title</h1>
    <div id="content">
        <div>$Book.AverageRatingStars</div>
        <br>
        <p>
            by 
            <% loop $Book.Authors %>
                $Name<% if not $IsLast %><% if $FromEnd == 2 %> and <% else %>, <% end_if %><% end_if %>
            <% end_loop %>
        </p>
        <p>
            $Book.DescriptionHTML
        </p>
        <br>
    </div>
    <div id="reviews" class="searchResults">

        <% if $Reviews %>
            <p class="searchQuery">Current Reviews</p>
            <ul id="SearchResults">

                <% loop $Reviews %>
                    <li>
                        <h4>$Title</h4>
                        <div>
                            <p>by $Member.FirstName</p>
                            <p>$RatingStars</p>
                        </div>                 
                        <p>$Review.FirstParagraph</p>
                    </li>
                <% end_loop %>

            </ul>
            <% if $Reviews.MoreThanOnePage %>
                <% if $Reviews.NotFirstPage %>
                    <a class="prev" href="$Reviews.PrevLink">Prev</a>
                <% end_if %>
                <% loop $Reviews.PaginationSummary %>
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
                <% if $Reviews.NotLastPage %>
                    <a class="next" href="$Reviews.NextLink">Next</a>
                <% end_if %>
            <% end_if %>
        <% end_if %>
    </div>
</section>


