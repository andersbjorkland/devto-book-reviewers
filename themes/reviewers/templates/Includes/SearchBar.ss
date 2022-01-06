<form class="Actions">
    <div class="line">
        <div class="field">
            <label for="search-input">Search</label>
            <input id="search-input" type="text" name="q" class="text" value="$Query">
        </div>
        <% if $Languages %>
        <div class="field">
            <label for="langRestriction">Language</label>
            <select name="langRestrict" id="langRestrict">
    
                <% loop $Languages %>
                <option value=$code <% if $Up.LangRestriction == $code %>selected<% end_if %>>$name</option>
                <% end_loop %>
    
            </select>
        </div>
        <% end_if %>
    </div>
    <div class="line">
        <div class="field">
            <input type="submit" class="btn" value="Search" />
        </div>
    </div>
</form>