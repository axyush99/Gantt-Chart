<!DOCTYPE html>
<html>
<head>
    <title>Gantt Chart Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .chart {
            border: 1px solid #ccc;
            margin-top: 20px;
            padding: 20px;
            width: 800px;
            overflow-x: auto; /* Add horizontal scroll for better visualization */
        }
        .task {
            margin-bottom: 20px; /* Increase margin to create space between tasks */
        }
        .task-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .bar {
            background-color: #007bff;
            border-radius: 5px;
            height: 20px;
            margin-top: 5px;
            position: absolute; /* Position bars absolutely */
        }
        .dependency {
            position: absolute;
            border: 1px solid #007bff;
            border-left: none;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            top: 0;
            bottom: 0;
            left: 100%;
        }
    </style>
</head>
<body>
    <h2>Gantt Chart Generator</h2>
    <form method="post">
        <div class="task">
            <label for="task_name">Task Name:</label>
            <input type="text" id="task_name" name="task_name[]">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date[]">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date[]">
            <label for="dependency">Dependency:</label>
            <select id="dependency" name="dependency[]">
                <option value="">None</option>
            </select>
        </div>
        <button type="button" id="add_task">Add Task</button>
        <button type="submit">Generate Gantt Chart</button>
    </form>

    <div class="chart" id="gantt_chart">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tasks = $_POST["task_name"];
        $start_dates = $end_dates = $dependencies = array();
        for ($i = 0; $i < count($_POST["task_name"]); $i++) {
            $start_dates[] = $_POST["start_date"][$i];
            $end_dates[] = $_POST["end_date"][$i];
            $dependencies[] = $_POST["dependency"][$i];
        }

        $max_date = max($end_dates);

        echo '<svg width="100%" height="500">';
        $x = 10; // Initial x position
        for ($i = 0; $i < count($tasks); $i++) {
            $start_date = strtotime($start_dates[$i]);
            $end_date = strtotime($end_dates[$i]);
            $width = (($end_date - $start_date) / (60 * 60 * 24)) * 10;
            $dependency = $dependencies[$i];

            echo '<g class="task">';
            echo '<text x="' . $x . '" y="20">' . $tasks[$i] . '</text>';
            echo '<rect class="bar" x="' . $x . '" y="30" width="' . $width . '" height="20"></rect>';

            if (!empty($dependency)) {
                $dependency_x = array_search($dependency, $tasks) * 100;
                echo '<line x1="' . $dependency_x . '" y1="40" x2="' . ($x + 5) . '" y2="40" style="stroke:#007bff;stroke-width:2" />';
            }

            echo '</g>';

            $x += $width + 20; // Increase x by width plus some margin
        }
        echo '</svg>';
    }
    ?>
    </div>

    <script>
        document.getElementById("add_task").addEventListener("click", function () {
            var taskDiv = document.createElement("div");
            taskDiv.classList.add("task");

            var nameLabel = document.createElement("label");
            nameLabel.textContent = "Task Name:";
            var nameInput = document.createElement("input");
            nameInput.type = "text";
            nameInput.name = "task_name[]";

            var startDateLabel = document.createElement("label");
            startDateLabel.textContent = "Start Date:";
            var startDateInput = document.createElement("input");
            startDateInput.type = "date";
            startDateInput.name = "start_date[]";

            var endDateLabel = document.createElement("label");
            endDateLabel.textContent = "End Date:";
            var endDateInput = document.createElement("input");
            endDateInput.type = "date";
            endDateInput.name = "end_date[]";

            var dependencyLabel = document.createElement("label");
            dependencyLabel.textContent = "Dependency:";
            var dependencySelect = document.createElement("select");
            dependencySelect.name = "dependency[]";
            var noneOption = document.createElement("option");
            noneOption.value = "";
            noneOption.textContent = "None";
            dependencySelect.appendChild(noneOption);

            var tasks = document.querySelectorAll('input[name^="task_name"]');
            tasks.forEach(function(task) {
                var option = document.createElement("option");
                option.value = task.value;
                option.textContent = task.value;
                dependencySelect.appendChild(option);
            });

            taskDiv.appendChild(nameLabel);
            taskDiv.appendChild(nameInput);
            taskDiv.appendChild(startDateLabel);
            taskDiv.appendChild(startDateInput);
            taskDiv.appendChild(endDateLabel);
            taskDiv.appendChild(endDateInput);
            taskDiv.appendChild(dependencyLabel);
            taskDiv.appendChild(dependencySelect);

            document.querySelector("form").insertBefore(taskDiv, document.getElementById("add_task"));
        });
    </script>
</body>
</html>
