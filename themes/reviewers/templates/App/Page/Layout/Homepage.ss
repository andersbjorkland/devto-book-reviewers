<div class="line">
    $SearchBookForm
</div>
<div class="line">
    <br>
    <% if $Query %>
        <h2>Search results for $Query</h2>
        <br>
        <p>
            <% loop $BookQuery($Query) %>
                <h3>$Title</h3>
            <% end_loop %>
        </p>
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
