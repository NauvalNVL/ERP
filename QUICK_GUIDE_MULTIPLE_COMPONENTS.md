# Quick Guide: Creating Master Cards with Multiple Components

## Overview
This guide shows you how to create a master card with multiple component configurations (Main, Fit1, Fit2, etc.).

## Step-by-Step Instructions

### 1. Start New Master Card

1. Navigate to **Update MC** page
2. Click the search icon next to **AC#** field
3. Select your customer from the list
4. Enter a new **MCS#** (Master Card Sequence number)
5. Click **"Proceed"** button

### 2. Configure Main Component

1. Fill in required fields:
   - **MC Model**: Product model name
   - **MC Short Model**: Abbreviated model name
2. Click **"Next Setup"** button
3. Setup MC Component modal opens with **Main** component selected

### 3. Setup Product Design for Main Component

1. Click **"Setup PD"** button
2. In the Setup PD modal, configure:
   - **P/Design**: Select product design
   - **Flute**: Select paper flute type
   - **SO (Paper Quality)**: Click SO buttons to select paper quality for each layer
   - **WO (Paper Quality)**: Click WO buttons to select paper quality for each layer
   - **Other fields**: Fill in dimensions, colors, etc.
3. Click **"Save Master Card"** button
4. **Success!** You'll see: "Master Card saved successfully for component: Main"

### 4. Add Fit1 Component

1. In the Setup MC Component modal, click on **"Fit1"** button
2. The Fit1 component is now selected (highlighted)
3. Click **"Setup PD"** button
4. Configure product design for Fit1 (can be different from Main)
5. Click **"Save Master Card"** button
6. **Success!** You'll see: "Master Card saved successfully for component: Fit1"

### 5. Add More Components (Fit2-Fit9)

Repeat step 4 for each additional component you need:
- Click component button (Fit2, Fit3, etc.)
- Click "Setup PD"
- Configure product design
- Click "Save Master Card"

## Important Notes

### Component Selection
- Only ONE component can be selected at a time
- Selected component is highlighted in blue
- Each component can have different product design configuration

### Saving Behavior
- Each "Save Master Card" creates/updates ONE component
- Same MCS# can have multiple components
- Each component is stored as a separate row in the database

### Database Structure
```
Example: MCS# = "MC001", Customer = "C001"

After saving Main:
MC001 | C001 | Main | [configuration...]

After saving Fit1:
MC001 | C001 | Main | [configuration...]
MC001 | C001 | Fit1 | [configuration...]

After saving Fit2:
MC001 | C001 | Main | [configuration...]
MC001 | C001 | Fit1 | [configuration...]
MC001 | C001 | Fit2 | [configuration...]
```

## Common Scenarios

### Scenario 1: Box with Divider
- **Main**: Outer box configuration
- **Fit1**: Inner divider configuration

### Scenario 2: Box with Lid and Insert
- **Main**: Box body
- **Fit1**: Lid
- **Fit2**: Insert/padding

### Scenario 3: Multi-part Assembly
- **Main**: Base component
- **Fit1-Fit4**: Additional parts
- Each part can have different:
  - Product design
  - Paper quality
  - Dimensions
  - Colors

## Troubleshooting

### Error: "Session expired. Please refresh the page and try again."
**Solution:** 
1. Refresh the page (F5 or Ctrl+R)
2. Re-select your customer
3. Search for your MCS#
4. Continue configuration

### Error: "Please select Customer Account (AC#) first"
**Solution:**
1. Click the search icon next to AC# field
2. Select customer from the list
3. Then proceed with MCS# entry

### Component not saving
**Solution:**
1. Verify component is selected (highlighted in blue)
2. Ensure all required fields are filled
3. Check for validation errors
4. Try refreshing the page if issue persists

## Tips for Efficiency

1. **Plan Your Components**: Before starting, list all components you need
2. **Main First**: Always configure Main component first
3. **Similar Configurations**: If Fit components are similar, configure one then modify for others
4. **Save Frequently**: Save each component as you complete it
5. **Verify in Database**: After saving all components, verify in database:
   ```sql
   SELECT MCS_Num, COMP, MODEL, P_DESIGN 
   FROM MC 
   WHERE MCS_Num = 'YOUR_MCS_NUMBER'
   ORDER BY COMP;
   ```

## Visual Guide

```
┌─────────────────────────────────────────┐
│  Update MC Page                         │
├─────────────────────────────────────────┤
│  AC#: [C001        ] [🔍]              │
│  MCS#: [MC001      ] [🔍] [Proceed]    │
│                                         │
│  [Next Setup] ← Click after Proceed    │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│  Setup MC Component Modal               │
├─────────────────────────────────────────┤
│  [Main] [Fit1] [Fit2] ... [Fit9]       │
│   ↑                                     │
│  Selected (blue highlight)              │
│                                         │
│  [Setup PD] [Setup Others]              │
└─────────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│  Setup PD Modal                         │
├─────────────────────────────────────────┤
│  P/Design: [B1        ▼]               │
│  Flute: [B          ▼]                 │
│  SO: [SO1] [SO2] [SO3] [SO4] [SO5]     │
│  WO: [WO1] [WO2] [WO3] [WO4] [WO5]     │
│  ... (more fields)                      │
│                                         │
│  [Save Master Card] ← Saves current    │
│                        component only   │
└─────────────────────────────────────────┘
```

## Success Indicators

✅ **Correct:**
- "Master Card saved successfully for component: Main"
- "Master Card saved successfully for component: Fit1"
- Each component shows its own success message

❌ **Incorrect:**
- Generic "Master Card saved" without component name
- Same component saved multiple times
- Components overwriting each other

## Need Help?

If you encounter issues:
1. Check the browser console (F12) for error messages
2. Verify CSRF token exists: Open console and type:
   ```javascript
   document.querySelector('meta[name="csrf-token"]')
   ```
3. Check database to see what was actually saved
4. Contact system administrator with:
   - MCS# you're working on
   - Component you're trying to save
   - Error message (if any)
   - Screenshot of the issue
