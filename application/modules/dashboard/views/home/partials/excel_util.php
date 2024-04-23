      
                <script>


                    function html_table_to_excel(type) {
                        var data = document.getElementById('employee_data');

                        var file = XLSX.utils.table_to_book(data, { sheet: "sheet1" });

                        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

                        XLSX.writeFile(file, '<?= $uptitle ?>.' + type);
                    }

                    const export_button = document.getElementById('export_button');

                    export_button.addEventListener('click', () => {
                        html_table_to_excel('xlsx');
                    });

                </script>