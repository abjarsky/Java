import ace_tools as tools

# Retry creating the zip file
zip_file_path = "/mnt/data/Neuer_Ordner_Project.zip"

with zipfile.ZipFile(zip_file_path, 'w') as project_zip:
    for folder, files in project_structure.items():
        folder_path = os.path.join(base_path, folder)
        for file in files:
            project_zip.write(os.path.join(folder_path, file), 
                              os.path.relpath(os.path.join(folder_path, file), base_path))

tools.display_file(zip_file_path)
