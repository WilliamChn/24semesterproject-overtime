document.addEventListener('DOMContentLoaded', function () {
  // Removed addMealDropdown function and its call
  
  // Attach click event listeners to all "Add Food" buttons
  document.querySelectorAll('.add-food').forEach(function(button) {
    button.addEventListener('click', function() {
      let inputBox = document.createElement('input');
      inputBox.type = 'text';
      inputBox.placeholder = 'Enter food item';
      inputBox.className = 'food-input';

      let parentDiv = this.parentNode;
      parentDiv.insertBefore(inputBox, parentDiv.firstChild);
      inputBox.focus();

      inputBox.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && this.value.trim() !== '') {
          let foodItem = document.createElement('p');
          foodItem.textContent = this.value;
          parentDiv.insertBefore(foodItem, parentDiv.firstChild);
          this.remove();
        }
      });
    });
  });

  // Attach click event listeners to all "Delete Entry" buttons
  document.querySelectorAll('.delete-entry').forEach(function(button) {
    button.addEventListener('click', function() {
      let parentDiv = this.closest('.meal');
      let foodItem = parentDiv.querySelector('p, .food-input, .edit-input');
      if (foodItem) {
        foodItem.remove();
      }
    });
  });

  // Attach click event listeners to all "Edit" buttons
  document.querySelectorAll('.Edit').forEach(function(button) {
    button.addEventListener('click', function() {
      let foodItem = this.closest('.meal').querySelector('p');
      if (foodItem) {
        let editInput = document.createElement('input');
        editInput.type = 'text';
        editInput.value = foodItem.textContent;
        editInput.className = 'edit-input';
        foodItem.parentNode.replaceChild(editInput, foodItem);
        editInput.focus();

        editInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter' && this.value.trim() !== '') {
            foodItem.textContent = this.value;
            editInput.parentNode.replaceChild(foodItem, editInput);
          }
        });
      }
    });
  });
})