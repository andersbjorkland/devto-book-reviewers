<section class="container">
    <h1 class="text-center">Book Results</h1>
    <% include SearchBar %>

    <div class="container">

    <% if $Books %>
    <% loop $Books %>
        
        <% if $Modulus(3) == 1 %>
        <div class="row">
        <% end_if %>

        <div class="col-12 col-sm-6 col-md-4 p-4">
            <div class="card h-100 shadow">
                <img src="$image" alt="" class="card-img">
                <div class="card-body d-flex flex-column card-img-overlay h-50 p-4 mt-auto bg-frosted text-black rounded">
                    <div class="card-title">
                        <h4>$title</h4>
                    </div>
                    <div class="flex">
                        <% loop $authors %>
                        <p>$AuthorName</p>
                        <% end_loop %>
                    </div>
                    <button 
                        class="btn btn-dark mt-auto mx-auto" 
                        data-bs-toggle="modal" 
                        data-bs-target="#bookModal-$Pos"    
                    >Description</button>
                </div>
            </div>
        </div>
        <div id="bookModal-$Pos" class="modal fade" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="bookModalLabel" class="modal-title">$title</h5>
                        <button 
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                    <p>$description</p>
                    </div>
                    <div class="modal-footer">
                        <button 
                            class="btn btn-secondary" 
                            type="button"
                            data-bs-dismiss="modal"
                        >Close</button>
                    </div>
                </div>
            </div>
        </div>

        <% if $Modulus(3) == 0 %>
        </div>   
        <% end_if %>

    <% end_loop %>
    <% loop $Pagination %>
    <div class="d-flex justify-content-center p-4">
        <ul class="pagination gap-4">
            <% if $start.link %>
            <li class="page-item">
                <a href="$start.link" class="page-link">|<</a> 
            </li>                       
            <% end_if %>
            <% if $previous.link %>
            <li class="page-item">
                <a href="$previous.link" class="page-link"><</a>                        
            </li>
            <% end_if %>

            <% loop $pages %>
                <% if $currentPage %>
                <li class="page-item active">
                    <a href="$link" class="page-link">$page</a>                        
                </li>
                <% else %>
                <li class="page-item">
                    <a href="$link" class="page-link">$page</a>                        
                </li>
                <% end_if %>
            <% end_loop %>

            <% if $next.link %>
            <li class="page-item">
                <a href="$next.link" class="page-link">></a>   
            </li>                     
            <% end_if %>
            <% if $start.link %>
            <div class="col-1"></div>
            <% end_if %>
        </ul>
    </div>    
    <% end_loop %>
    
    <% end_if %>

    </div>
</section>

