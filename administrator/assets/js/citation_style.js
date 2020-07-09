/**
 * Sorts jsonObject by first value and return sorted array
 *
 * @param jsonObject JSON object to be sorted by value
 * @return sorted array [[value, key], [value, key], ...]
 */
function sortByValue(jsonObject) {
    let sortedArray = [];
    for (let jsonEntry in jsonObject)
        if (jsonObject.hasOwnProperty(jsonEntry))
            // push each JSON object entry in array by [value, key]
            sortedArray.push([jsonObject[jsonEntry], jsonEntry]);

    return sortedArray.sort((a, b) => a[0].toLowerCase().localeCompare(b[0].toLowerCase()));
}

/**
 * Creates all blocks for each tab from blockArray with class
 *
 * @param tabIds The ids of the tabs
 * @param blockArray The array of [String, id] to create a block from
 * @param className Class for block
 */
function createBlocks(tabIds, blockArray, className) {
    blockArray.forEach(sortedArrayEntry => {
        Array.from(tabIds).forEach(ol => {
            const li = createLiElement("block", className, sortedArrayEntry[1])
            li.appendText(String.from(sortedArrayEntry[0]));
            ol.appendChild(li);
        });
    });
}

/**
 * Creates a li-DOM element with classes
 *
 * @param classes The class strings for the li element
 * @return lo The DOM-element with classes
 */
function createLiElement(...classes) {
    const li = document.createElement("li");
    classes.forEach(string => li.addClass(string));
    return li;
}

/**
 * If a citation style is available, it loads it's blocks into the views.
 */
function loadItems() {
    // Checks whether the citation style has blocks to display
    if (document.getElementById("jform_string").value !== "") {
        // Grabs citation style and maps default citation style (-1) to id 1
        citationStyle = JSON.parse(document.getElementById("jform_string").value);
        citationStyle[1] = citationStyle[-1];

        // For each reference type fill its tab
        reference_type_ids.forEach(id => loadReferenceTypeTab(id));
    }
}

/**
 * Loads blocks from reference type into a tab
 *
 * @param id Reference type id
 */
function loadReferenceTypeTab(id) {

    hideAuthorArea(id);
    emptyAllDropZones(id);

    // is reference type in citation style defined
    if (citationStyle.hasOwnProperty(id)) {
        let referenceTypeInCitationStyle = citationStyle[id];

        // Contains author?
        if (referenceTypeInCitationStyle.includes(1)) {
            // splits blocks for author logic
            // blocks to author
            let indexFirstAuthor = referenceTypeInCitationStyle.indexOf(1);
            let blocksToAuthor = referenceTypeInCitationStyle.slice(0, indexFirstAuthor).concat([-3]);
            let blocksRemaining = referenceTypeInCitationStyle.slice(indexFirstAuthor + 1);

            // first author blocks + delimiter
            let indexMiddleAuthor = blocksRemaining.indexOf(2);
            let blocksFirstAuthor = blocksRemaining.slice(0, indexMiddleAuthor);
            let specialChar = blocksRemaining.slice(indexMiddleAuthor + 1, indexMiddleAuthor + 2);
            blocksRemaining = blocksRemaining.slice(indexMiddleAuthor + 2);

            // middle author blocks
            let indexLastAuthor = blocksRemaining.indexOf(3);
            let blocksMiddleAuthor = blocksRemaining.slice(0, indexLastAuthor);
            blocksRemaining = blocksRemaining.slice(indexLastAuthor + 1);

            // last author
            let indexRemainingBlocks = blocksRemaining.indexOf(4);
            let blocksLastAuthor = blocksRemaining.slice(0, indexRemainingBlocks);
            let blocksFollowingAuthor = blocksRemaining.slice(indexRemainingBlocks + 1);

            // non author blocks
            let blocksNonAuthor = blocksToAuthor.concat(blocksFollowingAuthor);

            showAuthorArea(id);

            // process non author blocks
            const blockListMain = document.getElementById("orderedList_" + id);
            blocksNonAuthor.forEach(blockNonAuthor =>
                processNonAuthorBlocks(id, blockNonAuthor, blockListMain));

            // process first author blocks
            const blockListFirstAuthor = document.getElementById("orderedAuthorList1_" + id);
            blocksFirstAuthor.forEach(blockFirstAuthor =>
                processAuthorBlocks(id, blockFirstAuthor, blockListFirstAuthor, "clonedAuthor1", "clonedCharacter1"));

            // process middle author blocks
            const blockListMiddleAuthor = document.getElementById("orderedAuthorList2_" + id);
            blocksMiddleAuthor.forEach(blockMiddleAuthor =>
                processAuthorBlocks(id, blockMiddleAuthor, blockListMiddleAuthor, "clonedAuthor2", "clonedCharacter2"));

            // process last author blocks
            const blockListLastAuthor = document.getElementById("orderedAuthorList3_" + id);
            blocksLastAuthor.forEach(blockLastAuthor =>
                processAuthorBlocks(id, blockLastAuthor, blockListLastAuthor, "clonedAuthor3", "clonedCharacter3"));

            // process special character blocks
            const blockListSpecialCharacter = document.getElementById("orderedAuthorList4_" + id);
            specialChar.forEach(blockSpecialCharacter =>
                processSpecialCharacterBlocks(id, blockSpecialCharacter, blockListSpecialCharacter));

        } else {
            // process non author blocks
            const blockListMain = document.getElementById("orderedList_" + id);
            referenceTypeInCitationStyle.forEach(blockNonAuthor =>
                processNonAuthorBlocks(id, blockNonAuthor, blockListMain));
        }
    }
}

/**
 * Hides the author area for a tab
 *
 * @param id The tab id where the area should be hidden
 */
function hideAuthorArea(id) {
    document.getElementById("authorArea_" + id).style.display = "none";
    document.getElementById("clonedAuthorArea_" + id).style.display = "none";
}

/**
 * Shows the author area for a tab
 *
 * @param id The tab id where the area should be shown
 */
function showAuthorArea(id) {
    document.getElementById("authorArea_" + id).style.display = "flex";
    document.getElementById("clonedAuthorArea_" + id).style.display = "flex";
}

/**
 * Removes all blocks from each drop zone for a tab
 *
 * @param id The tab id where the drop zones should be emptied
 */
function emptyAllDropZones(id) {
    jQuery("#orderedList_" + id).empty();
    emptyAuthorDropZones(id);
}

/**
 * Removes all blocks from the author drop zone for a tab
 *
 * @param id The tab id where the author drop zone should be emptied
 */
function emptyAuthorDropZones(id) {
    jQuery("#orderedAuthorList1_" + id).empty();
    jQuery("#orderedAuthorList2_" + id).empty();
    jQuery("#orderedAuthorList3_" + id).empty();
}

/**
 * Makes the li element draggable and adds a remove function
 *
 * @param id The tab id where the block is used
 * @param li The li-DOM element which should be draggable
 */
function makeDraggable(id, li, sortable) {
    jQuery(li).draggable({
        connectToSortable: sortable,

        // remove block from list and clean up author drop zone
        revert: function (valid) {
            if (!valid) {
                if (this.hasClass("-3")) {
                    emptyAuthorDropZones(id);
                    hideAuthorArea(id);
                    jQuery(".-3").addClass("original");
                }
                this.remove();
                document.getElementById("jform_string").value = "";
            } else {
                return true;
            }
        },
    });
}

/**
 * Loads non author blocks from reference type into a tab
 *
 * @param id Reference type id
 * @param nonAuthorBlock The block to be process
 * @param blockList The ol element to add the block to
 */
function processNonAuthorBlocks(id, nonAuthorBlock, blockList) {
    const li = createLiElement("clonedBlock", nonAuthorBlock);

    // db view field or special character block
    if (nonAuthorBlock in blocks) {
        li.addClass("cloned");
        li.appendText(blocks[nonAuthorBlock]);
    } else {
        li.addClass("clonedCharacter");
        li.appendText(specialBlocks[nonAuthorBlock]);
    }

    // make block draggable
    makeDraggable(id, li, blockList);

    // add block to list
    blockList.appendChild(li);
}

/**
 * Loads author blocks from reference type into a tab
 *
 * @param id Reference type id
 * @param blockAuthor The block to be process
 * @param blockListAuthor The ol element to add the block to
 * @param classNameAuthor The class to be added to the author block
 * @param classNameCharacter The class to be added to the character block
 */
function processAuthorBlocks(id, blockAuthor, blockListAuthor, classNameAuthor, classNameCharacter) {
    const li = createLiElement("clonedBlock", blockAuthor);

    // author block or special character block
    if (blockAuthor in authorBlocks) {
        li.addClass(classNameAuthor);
        li.appendText(authorBlocks[blockAuthor]);
    } else {
        li.addClass(classNameCharacter);
        li.appendText(specialBlocks[blockAuthor]);
    }

    makeDraggable(id, li, blockListAuthor);
    blockListAuthor.appendChild(li);
}

/**
 * Loads special blocks from reference type into a tab
 *
 * @param id Reference type id
 * @param blockSpecialCharacter The block to be process
 * @param blockListSpecialCharacter The ol element to add the block to
 */
function processSpecialCharacterBlocks(id, blockSpecialCharacter, blockListSpecialCharacter) {
    const li = createLiElement("clonedBlock", blockSpecialCharacter, "clonedCharacter4");
    li.appendText(specialBlocks[blockSpecialCharacter]);

    makeDraggable(id, li, blockListSpecialCharacter);
    blockListSpecialCharacter.appendChild(li);
}

/**
 * Gets the id of a block
 *
 * @param blockListEntry block to get id from
 * @return {number} id of block
 */
function mapBlockToId(blockListEntry) {
    // Is block -> return block id
    for (const block in blocks)
        if (blocks.hasOwnProperty(block) && blockListEntry.hasClass(block))
            return parseInt(block);

    // Is special block -> return special block id
    for (const specialBlock in specialBlocks)
        if (specialBlocks.hasOwnProperty(specialBlock) && blockListEntry.hasClass(specialBlock))
            return parseInt(specialBlock)

    // Is author block -> return author block id
    for (const authorBlock in authorBlocks)
        if (authorBlocks.hasOwnProperty(authorBlock) && blockListEntry.hasClass(authorBlock))
            return parseInt(authorBlock)
}

/**
 * Gets the ids of a block list
 *
 * @param blockList blocks to get ids from
 * @return {[number]} ids of blocks
 */
function mapBlocksToIds(blockList) {
    let tmp = [];
    Array.from(blockList.getElementsByTagName("li")).forEach(blockListEntry =>
        tmp.push(mapBlockToId(blockListEntry)));
    return tmp;
}

/**
 * On drop of block function
 * Adds dragged block to list
 *
 * @param id Id of the tab
 * @param ui Dragged block
 * @param clonedBlockType Block type class to add
 * @param clonedCharacter Character type class to add
 * @param listToAddTo ol list to add block to
 * @param originalBlockType Block type class to be replaced
 */
function drop(id, ui, clonedBlockType, clonedCharacter, listToAddTo, originalBlockType) {
    document.getElementById("jform_string").value = "";
    // Is block draggable
    if (jQuery(ui.draggable).hasClass(originalBlockType) || jQuery(ui.draggable).hasClass("originalCharacter")) {
        //Clone block and modify classes
        let clonedBlock = ui.draggable.clone()[0];
        jQuery(clonedBlock).removeClass("block");
        jQuery(clonedBlock).addClass("clonedBlock");
        if (jQuery(ui.draggable).hasClass(originalBlockType)) {
            jQuery(clonedBlock).addClass(clonedBlockType);
            jQuery(clonedBlock).removeClass(originalBlockType);
        } else {
            jQuery(clonedBlock).addClass(clonedCharacter);
            jQuery(clonedBlock).removeClass("originalCharacter");
        }
        // Makes cloned block draggable and adds it to list
        const list = document.getElementById(listToAddTo + "_" + id);
        makeDraggable(id, clonedBlock, list);
        list.append(clonedBlock);

        // Shows author area if needed
        if (jQuery(ui.draggable).hasClass("-3")) {
            jQuery(ui.draggable).removeClass("original");
            showAuthorArea(id);
        }
    }
}

/**
 * Generate savable json from blocks in tabs
 * Writes json to actual Joomla! save field
 */
function submitClicked() {
    const submitField = document.getElementById("jform_string");

    if (submitField.value === "") {
        let dict = {};
        // each reference type
        reference_type_ids.forEach(id => {
            arrayString = mapBlocksToIds(document.getElementById("orderedList_" + id));

            let dictArray;
            // replaced the author placeholder, if available
            if (arrayString.includes(-3)) {
                arrayStringAuthor1 = mapBlocksToIds(document.getElementById("partAuthor1_" + id));
                arrayStringAuthor2 = mapBlocksToIds(document.getElementById("partAuthor2_" + id));
                arrayStringAuthor3 = mapBlocksToIds(document.getElementById("partAuthor3_" + id));
                arrayStringAuthor4 = mapBlocksToIds(document.getElementById("partAuthor4_" + id));

                let indexAuthorPlaceholder = arrayString.indexOf(-3);
                let blocksBeforeAuthor = arrayString.slice(0, indexAuthorPlaceholder);
                let blocksAfterAuthor = arrayString.slice(indexAuthorPlaceholder + 1);
                let blocksAuthor = [1].concat(arrayStringAuthor1).concat([2]).concat(arrayStringAuthor4).concat(arrayStringAuthor2).concat([3]).concat(arrayStringAuthor3).concat([4]);

                dictArray = blocksBeforeAuthor.concat(blocksAuthor).concat(blocksAfterAuthor);
            } else {
                dictArray = arrayString;
            }

            // maps default to -1
            if (dictArray.length > 0) {
                if (id === 1) id = -1;
                dict[id] = dictArray;
            }
        });

        submitField.value = JSON.stringify(dict);
        submitField.innerText = JSON.stringify(dict);
    }
}