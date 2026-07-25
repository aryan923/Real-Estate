# Workspace Customization Rules

## Card Styling & Layout Rules
- **No Full-Width Stretching**: Enquiry detail cards (Lead Information, Requirement Details, Budget & Financial) MUST ALWAYS remain compact (`w-[310px]` or `max-w-md`). NEVER stretch them 100% full width across the container under any circumstances.
- **Horizontal Side-by-Side Layout**: In the Overview tab, the three cards (`Lead Information`, `Requirement Details`, `Budget & Financial`) MUST be stacked horizontally side-by-side using `flex flex-wrap items-start gap-2`.
- **Equal Internal Padding**: Inside card containers, left and right padding MUST remain equal (`p-4`).
- **Table Data Format**: Key-value pairs inside cards MUST be formatted using HTML `<table>` layout with 2 columns (`td` key, `td` value).
- **Top Metric Cards Fixed Width**: The top 6 info metric boxes (`Requirement`, `Budget`, `Location`, `Possession`, `Purpose`, `Status`) MUST ALWAYS be fixed `w-[151px]` with `gap-2` in a `w-[946px]` flex container to align 100% perfectly with the 946px width of the 3 cards below. NEVER stretch metric boxes 100% full width across the screen or collapse them into a full-width vertical stack.
