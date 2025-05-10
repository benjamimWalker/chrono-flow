import csv
import random
from datetime import datetime, timedelta

filename = 'work_logs_max_variety.csv'

first_names = ['John', 'Jane', 'Alice', 'Bob', 'Carlos', 'Eve', 'Linda', 'Mike', 'Sara', 'Victor', 'Nina', 'Tom', 'Amir', 'Wei', 'Aiko', 'Fatima', 'Lucia', 'Jamal']
last_names = ['Doe', 'Smith', 'Johnson', 'Brown', 'Diaz', 'Turner', 'Kim', 'Martinez', 'Nguyen', 'Lee', 'Kumar', 'Lopez', 'Hassan', 'Wong', 'Sato']

prefixes = ['Database', 'Frontend', 'Backend', 'API', 'DevOps', 'Mobile', 'Cloud', 'Security', 'UX/UI', 'Analytics']
actions = ['optimization', 'bug fixing', 'development', 'review', 'testing', 'deployment', 'integration', 'migration']
suffixes = ['task', 'workflow', 'issue', 'patch', 'module', 'feature', 'update', 'request']

clients = ['Acme Corp', 'Globex', 'Initech', 'Umbrella Co', 'Soylent', 'Wayne Enterprises']
task_ids = [f"#{random.randint(1000, 9999)}" for _ in range(100)]

start_date = datetime.strptime('2024-11-01', '%Y-%m-%d')
max_days = 180

with open(filename, mode='w', newline='', encoding='utf-8') as csvfile:
    writer = csv.writer(csvfile, quoting=csv.QUOTE_ALL)

    for _ in range(1_000_000):
        name = f"{random.choice(first_names)} {random.choice(last_names)}"
        offset = random.randint(0, max_days)
        date = start_date + timedelta(days=offset)
        hours = round(random.uniform(4.0, 9.0), 2)
        if hours % 0.25 != 0:
            hours = round(round(hours * 4) / 4, 2)

        hours_str = f"{hours} hours"
        desc = f"{random.choice(prefixes)} {random.choice(actions)} {random.choice(suffixes)}"
        if random.random() > 0.5:
            desc += f" for {random.choice(clients)}"
        if random.random() > 0.7:
            desc += f" ({random.choice(task_ids)})"

        writer.writerow([
            name,
            date.strftime('%Y-%m-%d'),
            hours_str,
            desc
        ])

print(f"Generated: {filename} with 1,000,000 richly varied records")

