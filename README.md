
# Voting System

Welcome to the Voting System project! This web application is designed to manage and facilitate voting processes with features implemented in PHP, MySQL, and JavaScript.

## Getting Started

To get started with the Voting System project, follow these steps:

### Prerequisites

- PHP version 8
- MySQL database

### Installation

1. Clone the repository using the following Git command:

   ```bash
   git clone https://github.com/salonisavner1174/votingSystem.git
   ```

2. Navigate to the project directory:

   ```bash
   cd votingSystem
   ```

3. Change into the admin directory:

   ```bash
   cd admin
   ```

4. Open the `config.php` file located at `admin\inc\config.php` in a text editor.

   ```php
   $db = mysqli_connect("HOST", "USERNAME", "PASSWORD", "DATABASE") or die("Connectivity Failed");
   ```

5. Update the following placeholders with your database information:

   - `HOST`: Your database host (e.g., localhost)
   - `USERNAME`: Your database username
   - `PASSWORD`: Your database password
   - `DATABASE`: Your database name

   Save the changes.

### Usage

Now that you have set up the database configuration, you can start using the Voting System project. Ensure that your web server is configured to serve PHP files and navigate to the project's URL in your web browser.

```

Make sure to replace the placeholders like `HOST`, `USERNAME`, `PASSWORD`, and `DATABASE` with your actual database configuration details.