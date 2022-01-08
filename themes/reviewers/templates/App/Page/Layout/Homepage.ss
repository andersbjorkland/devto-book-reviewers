<section class="container">
    <div class="Actions line">
        $SearchBookForm
    </div>
    <div id="Content" class="searchResults line">
        <% if $Query %>
            <p class="searchQuery">Results for "$Query"</p>
            <ul id="SearchResults">

                <% loop $BookQuery($Query) %>
                    <li>
                        <h4>
                            $Title
                        </h4>
                        <div>
                            <% loop $authors %>
                                <p>$AuthorName</p>
                            <% end_loop %>
                        </div>
                        <p>
                            Average rating: $Up.AverageRating($ID) <br>
                            <a href="#"><b>View reviews</b> ($Reviews.Count)</a>
                        </p>             
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
    <div class="line">
        <br>
        <h2>Latest Reviews</h2>
        <% loop $LatestReviews %>
            <div>
                <h3>$Book.Title</h3>
                <p>
                    <b>$Title</b> <br>
                    $Review.FirstParagraph <br>
                    $Up.RatingStars($Rating)
                </p>
            </div>
        <% end_loop %>
    </div>
</section>