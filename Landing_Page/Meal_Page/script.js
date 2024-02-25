// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function () {
    // Attach click event listeners to all "Add Food" buttons
    document.querySelectorAll('.add-food').forEach(function(button) {
      button.addEventListener('click', function() {
        // Logic for adding food
        console.log('Add food clicked');
        // You can add your code here to handle adding food
      });
    });
  
    // Attach click event listeners to all "Delete Entry" buttons
    document.querySelectorAll('.delete-entry').forEach(function(button) {
      button.addEventListener('click', function() {
        // Logic for deleting an entry
        console.log('Delete entry clicked');
        // You can add your code here to handle deleting an entry
        // For example, to delete the meal entry:
        this.closest('.meal').remove();
      });
    });
  
    // Attach click event listeners to all "Edit" buttons
    document.querySelectorAll('.Edit').forEach(function(button) {
      button.addEventListener('click', function() {
        // Logic for editing an entry
        console.log('Edit clicked');
        // You can add your code here to handle editing an entry
      });
    });
  });
  