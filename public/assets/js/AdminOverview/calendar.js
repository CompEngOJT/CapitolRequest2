/**
 * Calendar initialization for AdminOverview page
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Calendar.js loaded');
    
    // Get calendar element
    var calendarEl = document.getElementById('calendar');
    
    // Verify calendar element exists
    if (!calendarEl) {
        console.error('Calendar element not found!');
        return;
    }
    
    console.log('Calendar element found:', calendarEl);
    
    // Make sure FullCalendar is loaded
    if (typeof FullCalendar === 'undefined') {
        console.error('FullCalendar library not loaded!');
        return;
    }
    
    console.log('FullCalendar library detected');
    
    try {
        // Philippine holidays for 2025 - corrected dates
        var philippineHolidays = [
            // January
            { title: "New Year's Day", start: '2025-01-01' },
            { title: "Chinese New Year", start: '2025-01-29' }, // This date is correct
            
            // February
            { title: "EDSA People Power Revolution", start: '2025-02-25' },
            
            // March
            { title: "Ramadan likely begins", start: '2025-03-01' }, // Approximate date
            
            // April
            { title: "Day of Valor (Araw ng Kagitingan)", start: '2025-04-09' },
            { title: "Eid al-Fitr (End of Ramadan)", start: '2025-04-01' }, // Approximate date
            { title: "Maundy Thursday", start: '2025-04-17' },
            { title: "Good Friday", start: '2025-04-18' },
            { title: "Black Saturday", start: '2025-04-19' },
            { title: "Easter Sunday", start: '2025-04-20' },
            
            // May
            { title: "Labor Day", start: '2025-05-01' },
            { title: "Eid al-Adha", start: '2025-05-30' }, // Approximate date
            
            // June
            { title: "Independence Day", start: '2025-06-12' },
            
            // July
            { title: "Muharram/Islamic New Year", start: '2025-07-29' }, // Approximate date
            
            // August
            { title: "Ninoy Aquino Day", start: '2025-08-21' },
            { title: "National Heroes Day", start: '2025-08-25' },
            
            // October
            { title: "Indigenous Peoples' Day", start: '2025-10-13' },
            
            // November
            { title: "All Saints' Day", start: '2025-11-01' },
            { title: "All Souls' Day", start: '2025-11-02' },
            { title: "Bonifacio Day", start: '2025-11-30' },
            
            // December
            { title: "Feast of the Immaculate Conception", start: '2025-12-08' },
            { title: "Christmas Day", start: '2025-12-25' },
            { title: "Rizal Day", start: '2025-12-30' },
            { title: "New Year's Eve", start: '2025-12-31' }
        ];
        
        // Initialize calendar with configuration
        var calendar = new FullCalendar.Calendar(calendarEl, {
            // View settings
            initialView: 'dayGridMonth',
            
            // Toolbar configuration
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            },
            
            // Height settings 
            height: 300,
            contentHeight: 270,
            aspectRatio: 1.35,
            
            // Display options
            fixedWeekCount: false,
            showNonCurrentDates: true,
            dayMaxEvents: true,
            navLinks: false,
            
            // Formatting
            titleFormat: { year: 'numeric', month: 'long' },
            dayHeaderFormat: { weekday: 'short' },
            
            // Set Philippine holidays as events
            events: philippineHolidays,
            
            // Calendar behavior
            firstDay: 0, // Start week on Sunday
            
            // When calendar is initially rendered
            viewDidMount: function() {
                setTimeout(function() {
                    calendar.updateSize();
                }, 50);
            },
            
            // Important: After calendar has finished rendering all dates
            datesSet: function() {
                setTimeout(function() {
                    colorEventDays();
                }, 100);
            },
            
            // Handle event rendering but without displaying events
            eventDidMount: function(info) {
                // Hide the event but keep its data
                info.el.style.display = 'none';
            }
        });
        
        // Function to color days with events - fixed to avoid coloring empty cells
        function colorEventDays() {
            // First, remove any existing event classes and tooltips
            document.querySelectorAll('.has-event').forEach(function(el) {
                el.classList.remove('has-event');
                if ($(el).data('bs.tooltip')) {
                    $(el).tooltip('dispose');
                }
            });
            
            document.querySelectorAll('.holiday-date').forEach(function(el) {
                el.classList.remove('holiday-date');
            });
            
            // Get current view's events
            var events = calendar.getEvents();
            
            // Process each event
            events.forEach(function(event) {
                if (!event.start) return;
                
                // Format date as YYYY-MM-DD for comparison
                var year = event.start.getFullYear();
                var month = (event.start.getMonth() + 1).toString().padStart(2, '0');
                var day = event.start.getDate().toString().padStart(2, '0');
                var dateStr = `${year}-${month}-${day}`;
                
                // Find the corresponding day cell using data-date attribute
                var dayCell = document.querySelector(`.fc-day[data-date="${dateStr}"]`);
                
                // If found, highlight it
                if (dayCell) {
                    // Only apply has-event class to cells that represent valid dates in the current month
                    // Check if the cell is part of the current month view (not empty and not from adjacent months)
                    if (!dayCell.classList.contains('fc-day-other')) {
                        dayCell.classList.add('has-event');
                        
                        // Add tooltip
                        $(dayCell).tooltip({
                            title: event.title,
                            placement: 'top',
                            container: 'body'
                        });
                        
                        // Also highlight the day number cell specifically
                        var dayNumberCell = dayCell.querySelector('.fc-daygrid-day-top');
                        if (dayNumberCell) {
                            dayNumberCell.classList.add('holiday-date');
                        }
                    }
                }
            });
        }
        
        // Render the calendar
        console.log('Rendering calendar...');
        calendar.render();
        
        // Force size update and initial coloring
        setTimeout(function() {
            calendar.updateSize();
            colorEventDays(); // Initial coloring
            
            // Additional layout fixes
            document.querySelectorAll('.fc-scrollgrid-sync-table').forEach(function(table) {
                table.style.height = 'auto';
            });
            
            // Adjust view container if needed
            var viewContainer = document.querySelector('.fc-view-harness');
            if (viewContainer && viewContainer.offsetHeight > 250) {
                viewContainer.style.height = '250px';
            }
        }, 200);
        
        // Handle prev/next month navigation
        document.querySelector('.fc-prev-button').addEventListener('click', function() {
            setTimeout(colorEventDays, 100);
        });
        
        document.querySelector('.fc-next-button').addEventListener('click', function() {
            setTimeout(colorEventDays, 100);
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            setTimeout(function() {
                calendar.updateSize();
                colorEventDays();
            }, 100);
        });
        
    } catch (error) {
        console.error('Error initializing calendar:', error);
        
        // Display error message in calendar container
        calendarEl.innerHTML = '<div style="padding:20px; color:#e53e3e; text-align:center;">' +
                              '<p>Failed to load calendar. Please refresh the page.</p>' +
                              '<p>Error: ' + error.message + '</p></div>';
    }
});