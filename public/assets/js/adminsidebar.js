// Create a new JavaScript file named sidebar.js
$(document).ready(function() {
    // Function to load content via AJAX
    function loadContent(url, targetSelector) {
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function() {
                // Show loading indicator
                $(targetSelector).html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading content...</p></div>');
            },
            success: function(response) {
                // Update the content area with the loaded content
                $(targetSelector).html(response);
                
                // Update active class on sidebar
                $('.menu-item').removeClass('active');
                
                // Find the corresponding menu item and set it as active
                $('.menu-item').each(function() {
                    if($(this).attr('href') === url) {
                        $(this).addClass('active');
                    }
                });
                
                // Update browser history without full page reload
                window.history.pushState({path: url}, '', url);
            },
            error: function(xhr, status, error) {
                $(targetSelector).html('<div class="alert alert-danger">Error loading content: ' + error + '</div>');
            }
        });
    }
    
    // Attach click event to all sidebar menu items
    $('.menu-item').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        // Skip AJAX if it's a "#" placeholder link
        if (url === '#') {
            return;
        }
        
        // Load content via AJAX
        loadContent(url, '#main-content');
    });
    
    // Handle browser back/forward buttons
    $(window).on('popstate', function(event) {
        if (event.originalEvent.state) {
            loadContent(event.originalEvent.state.path, '#main-content');
        }
    });
});