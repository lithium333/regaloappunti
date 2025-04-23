# Regalo Appunti Website BACKEND
PoliTO Regalo Appunti back-end

Following, APIs description
(every single API returns a JSON answer)

# API GET CONFIG
file: api_get_config.php

Return back cfg.json file content, which describes the menu structures, an example of that file:
  "courses" : {
  	"1st" : "1 ANNO TRIENNALE",
  	"lib" : "CREDITI LIBERI TRIENNALE",
  	"aer" : "TRIENNALE AEROSPAZIALE",
  	"bio" : "TRIENNALE BIOMEDICA"
  },
  
  "subcats" : {
  	"1st" : {
  		"an1" : "ANALISI 1",
  		"chim" : "CHIMICA",
  		"fis" : "FISICA 1"
  	},
  	"bio" : {
  		"bes" : "Strumentazione biomedica e sicurezza",
  		"stm" : "Scienze e Tecnologia dei Materiali"
  	}
  }

In the given example, the section "1 ANNO TRIENNALE" identified by '1st' ID has 3 subsections (ANALISI 1, CHIMICA, FISICA 1)

# API GET LIST
file: api_get_list.php
GET request parameters: id (the section id, e.g. '1st' according to first json example)

Get a json listing all items belonging to a section:
status: 0 normally, -1 if category given trough 'id' GET parameter is not valid
subs: contains a dictionary linking subsection id to subsection description
ext/int: list of 3-items vector having [link,description,subsection id], subsection id is set to NULL if not applicable

# API GET SEARCH
file: api_get_search.php
GET request parameters: id (the keyword to search)

Get a json listing all items matching the searched keyword:
status: 0 normally, -1 'id' GET parameter is empty or not specified
cat: list of 4-items vector having [section description, section id, subsection description, subsection id], last 2 subsection parameters are null if is the section itself compliant to search keyword, else, if these ast 2 parameters are set it means that is the sub section that match
ext/int: list of 4-items vector having [section id, subsection id (can be null), link, description]

# API WR INSERT
file: api_wr_insert.php
POST parameters: data (contains raw json string, description below)

Insert new item only internal (int) resource matching to a certain telegram group links

DATA json parameters:
cat: section where new item has to be stored
sub: sub section (optional)
link: link to resource to be added
desc: description of resource

Answer: json having only status parameter
0: ok
-1: cat not valid
-2: subcat (when specified) not valid
-3: link failed checking (not compliant to requirement, e.g. not in the expected telegram group)
-4: file lock failed during DB write, no writes are done
