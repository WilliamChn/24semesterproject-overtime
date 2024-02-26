// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function () {
  // Attach click event listeners to all "Add Food" buttons
  document.querySelectorAll('.add-food').forEach(function(button) {
      button.addEventListener('click', function() {
          // Create an input box to enter food item
          let inputBox = document.createElement('input');
          inputBox.type = 'text';
          inputBox.placeholder = 'Enter food item';
          inputBox.className = 'food-input';

          // Insert the input box in the meal div, before the buttons
          let parentDiv = this.parentNode;
          parentDiv.insertBefore(inputBox, parentDiv.firstChild);

          // Focus on the input box to immediately start typing
          inputBox.focus();

          // Optional: Add a way to save the input (e.g., pressing Enter)
          inputBox.addEventListener('keypress', function(e) {
              if (e.key === 'Enter' && this.value.trim() !== '') {
                  // Logic to save the input value (you can adjust as needed)
                  let foodItem = document.createElement('p');
                  foodItem.textContent = this.value;
                  parentDiv.insertBefore(foodItem, parentDiv.firstChild);
                  this.remove(); // Remove input box after saving
              }
          });
      });
  });

  // Attach click event listeners to all "Delete Entry" buttons
  document.querySelectorAll('.delete-entry').forEach(function(button) {
      button.addEventListener('click', function() {
          // Find and delete the first <p> or input element in the meal div
          let parentDiv = this.closest('.meal');
          let foodItem = parentDiv.querySelector('p, .food-input, .edit-input');
          if (foodItem) {
              foodItem.remove(); // Remove the food item or input box
          }
      });
  });

  // Attach click event listeners to all "Edit" buttons
  document.querySelectorAll('.Edit').forEach(function(button) {
      button.addEventListener('click', function() {
          // Find the first <p> element in the meal div (assuming it's the food item)
          let foodItem = this.closest('.meal').querySelector('p');
          if (foodItem) {
              let editInput = document.createElement('input');
              editInput.type = 'text';
              editInput.value = foodItem.textContent;
              editInput.className = 'edit-input';

              // Replace the <p> element with the input box
              foodItem.parentNode.replaceChild(editInput, foodItem);

              // Focus on the input box
              editInput.focus();

              // Save the edited value when the user presses Enter
              editInput.addEventListener('keypress', function(e) {
                  if (e.key === 'Enter' && this.value.trim() !== '') {
                      foodItem.textContent = this.value;
                      editInput.parentNode.replaceChild(foodItem, editInput);
                  }
              });
          }
      });
  });
});