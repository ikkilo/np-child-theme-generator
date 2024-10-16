jQuery(document).ready(function ($) {
    // Function to handle the active theme logic
    function addChildThemeButton() {
        // Target the active theme
        var activeTheme = $('.theme-browser .active');

        if (activeTheme.length > 0) {
            // Get the theme slug from the active theme
            var themeSlug = activeTheme.data('slug');

            // Check if the button is already added to avoid duplicates
            if (activeTheme.find('.theme-action').length === 0) {
                // Create a new div for the "Generate Child Theme" button
                var newDiv = `
                    <div class="theme-action">
                        <a href="` + npctg_data.ajax_url + `?action=npctg_create_child_theme&theme=` + themeSlug + `&nonce=` + npctg_data.nonce + `" class="button button-primary" style="display:block; margin-top:10px;">Generate Child Theme</a>
                    </div>
                `;

                // Append the new div with the button below the active theme's theme-actions section
                activeTheme.find('.theme-screenshot').after(newDiv);
            }
        } else {
            console.error('Active theme not found.');
        }
    }

    // Use MutationObserver to watch for changes in the theme browser
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            // Check if there are any changes in the child elements of the theme browser
            if (mutation.type === 'childList' || mutation.type === 'attributes') {
                addChildThemeButton(); // Call the function to add the button
            }
        });
    });

    // Specify the target node and the observer options
    var targetNode = document.querySelector('.theme-browser');
    if (targetNode) {
        observer.observe(targetNode, {
            childList: true, // Watch for child nodes being added or removed
            subtree: true,   // Observe the entire subtree for changes
            attributes: true // Watch for attribute changes (e.g., class change)
        });
    } else {
        console.error('Theme browser not found.');
    }

    // Initial call to add the button if it's already needed
    addChildThemeButton();
});

